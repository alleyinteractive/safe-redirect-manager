<?php
/**
 * Migration reporting cli.
 *
 * @package CPR
 */

namespace CPR\Migration;

use WP_CLI;

/**
 * Migration Cleanup CLI command.
 */
class Migration_CLI extends \CLI_Command {

	use \Alleypack\CLI_Bulk_Post_Task;
	use \Alleypack\CLI_Helpers;

	/**
	 * Version control for migration.
	 *
	 * @var string
	 */
	public static $version = '2.0';

	/**
	 * Run all menu commands.
	 *
	 * ## EXAMPLES
	 *
	 * wp cpr-clean run_all
	 */
	public function run_all() {
		WP_CLI::runcommand( 'cpr-report status audio' );
	}

	/**
	 * Get the migration status for a feed.
	 *
	 * ## Options
	 *
	 * <feed_slug>
	 * : Feed to check the status of.
	 *
	 * [--migrate]
	 * : Attempt to migrate content that hasn't been.
	 *
	 * [--dry-run]
	 * : Don't actually do anything.
	 *
	 * ## EXAMPLES
	 *
	 *   $ wp cpr-migration feed_status audio --migrate
	 *
	 * @param array $args       CLI args.
	 * @param array $assoc_args CLI associate args.
	 */
	public function feed_status( $args, $assoc_args ) {

		// Migrate missing content?
		$migrate = ! empty( $assoc_args['migrate'] );
		$dry_run = ! empty( $assoc_args['dry-run'] );

		// Get the registered feed object.
		$feed_slug = $args[0] ?? '';
		$feed      = \Alleypack\Sync_Script\feed_manager()->get_feed( $feed_slug );
		if ( is_null( $feed ) ) {
			WP_CLI::error( "Invalid feed {$feed_slug}" );
		}

		$migration = Migration::instance();
		$migration->build_mapping( $feed->get_data_slug() );
		$ids = array_map(
			function( $filename ) {
				return str_replace( '.json', '', $filename );
			},
			$migration->data_mapping ?? []
		);

		$count = 0;

		array_map(
			function( $unique_id ) use ( $feed_slug, $migrate, $dry_run, &$count ) {
				if (
					! $this->get_status_by_unique_id( $unique_id )
					&& true === $migrate
				) {
					\WP_CLI::line( 'Attempting to migrate...' );
					\WP_CLI::line( '--------------------' );
					if ( ! $dry_run ) {
						WP_CLI::runcommand( "alleypack sync {$feed_slug} --unique_id={$unique_id}" );
						WP_CLI::runcommand( "alleypack sync content --unique_id={$unique_id}" );
					}
					\WP_CLI::line( '--------------------' );
					$this->get_status_by_unique_id( $unique_id );
				}

				// Stop the insanity.
				if ( $count >= 20 ) {
					\WP_CLI::line( 'Pausing to clear the cache' );
					$this->stop_the_insanity();
					$count = 0;
				}
				$count++;
			},
			$ids ?? []
		);
	}

	/**
	 * Determine the current status of a migration item using the unique id.
	 * This assumes a post (not applicable for terms or users).
	 *
	 * @param mixed $unique_id NID.
	 * @return bool True if we're on the up-and-up on the migration.
	 */
	public function get_status_by_unique_id( $unique_id ) {

		$query = new \WP_Query(
			[
				'fields'      => 'ids',
				'meta_key'    => 'alleypack_sync_script_unique_id',
				'meta_value'  => $unique_id,
				'post_status' => 'any',
				'post_type'   => 'any',
			]
		);

		$post_id = absint( $query->posts[0] ?? 0 );

		if ( 0 === $post_id ) {
			\WP_CLI::warning( "Object {$unique_id} has not been migrated" );
			return false;
		}

		$post_type       = get_post_type( $post_id );
		$content_version = get_post_meta( $post_id, 'cpr_block_conversion_mapping', true );
		$sync_version    = get_post_meta( $post_id, 'alleypack_sync_script_mapping_version', true );

		\WP_CLI::success( "Object {$unique_id} has been migrated to {$post_type} ({$post_id}). Sync ({$sync_version}), content is ({$content_version})" );

		if ( static::$version !== $sync_version ) {
			return false;
		}

		if ( Content\Feed_Item::get_mapping_version() !== $content_version ) {
			return false;
		}

		return true;
	}

	/**
	 * Clear all of the caches for memory management.
	 */
	private function stop_the_insanity() {
		global $wpdb, $wp_object_cache;

		$wpdb->queries = []; // Or define( 'WP_IMPORTING', true );.

		if ( ! is_object( $wp_object_cache ) ) {
			return;
		}

		$wp_object_cache->group_ops      = [];
		$wp_object_cache->stats          = [];
		$wp_object_cache->memcache_debug = [];
		$wp_object_cache->cache          = [];

		if ( is_callable( $wp_object_cache, '__remoteset' ) ) {
			$wp_object_cache->__remoteset(); // Important.
		}
	}
}
WP_CLI::add_command( 'cpr-migration', __NAMESPACE__ . '\Migration_CLI' );
