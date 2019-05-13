<?php
/**
 * Class to help manage migrations.
 *
 * @package CPR
 */

namespace CPR\Migration;

/**
 * Migration helpers and managers.
 */
class Migration {

	use \Alleypack\Singleton;

	/**
	 * Cache the data directory.
	 *
	 * @var array
	 */
	public $data_mapping = [];

	/**
	 * Easily work on the migration locally.
	 *
	 * @var string
	 */
	public $migration_scope = '';

	/**
	 * Array of feed slugs we want to load and register.
	 *
	 * @var array
	 */
	public $feeds = [
		'category',
		'document',
		'guest-author',
		'image',
		'job',
		'page',
		'post-tag',
		'service',
		'story',
		'underwriter',
		'user',
	];

	/**
	 * Constructor.
	 */
	public function setup() {

		if (
			isset( $_SERVER['HTTP_HOST'] )
			&& 'cpr.alley.test' === $_SERVER['HTTP_HOST']
		) {
			$this->migration_scope = 'partial';
		}

		// Load some AlleyPack modules.
		\Alleypack\load_module( 'attachments', '1.0' );
		\Alleypack\load_module( 'block-converter', '1.0' );
		\Alleypack\load_module( 'sync-script', '1.2' );

		$this->load_and_register_feeds();

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			require_once CPR_PATH . '/migration/cli/class-cleanup.php';
			require_once CPR_PATH . '/migration/cli/class-menus.php';
		}
	}

	/**
	 * Get the path to the data.
	 *
	 * @param string $slug Slug for directory to data.
	 * @return string
	 */
	public function get_data_directory( $slug ) {

		// Determine scope.
		if ( 'partial' === $this->migration_scope ) {
			$file_path = CPR_PATH . '/migration/test-data/';
		} else {
			$file_path = CPR_PATH . '/migration/data/';
		}

		$file_path .= "{$slug}/";

		if ( ! is_dir( $file_path ) ) {
			// phpcs:ignore
			wp_die( "No migration data found for {$slug}." );
		}

		return $file_path;
	}

	/**
	 * Helper to get a source data file by the directory offset.
	 *
	 * @param string $slug   Slug for directory to data.
	 * @param int    $offset File offset.
	 * @return array
	 */
	public function get_source_data_by_offset( string $slug, int $offset ) : array {

		if ( empty( $this->data_mapping[ $slug ] ) ) {
			$this->build_mapping( $slug );
		}

		$id = absint( $this->data_mapping[ $offset ] ?? 0 );

		return $this->get_source_data_by_id( $slug, $id );
	}

	/**
	 * Helper to get a source data file by the file id.
	 *
	 * @param string $slug Slug for directory to data.
	 * @param int    $id   Legacy ID.
	 * @return array
	 */
	public function get_source_data_by_id( string $slug, int $id ) : array {

		// Validate file exists.
		$file = $this->get_data_directory( $slug ) . "{$id}.json";
		if ( ! file_exists( $file ) ) {
			return [];
		}

		$json_string = file_get_contents( $file );
		return json_decode( $json_string, true );
	}

	/**
	 * Build the file position to legacy id mapping.
	 *
	 * @param string $slug Slug for directory to data.
	 */
	public function build_mapping( $slug ) {

		// Scan data directory for files.
		$files = scandir( $this->get_data_directory( $slug ) );

		if ( ! empty( $files ) ) {
			// Remove directories.
			$files = array_values(
				array_filter(
					array_map(
						function ( $file ) {
							if ( false !== strpos( $file, '.json' ) ) {
								return $file;
							}
						},
						$files
					)
				)
			);

			$this->data_mapping = $files;
		}
	}

	/**
	 * Load feed files and register.
	 */
	public function load_and_register_feeds() {

		// Generic feed since all our data is structured the same way.
		require_once CPR_PATH . '/migration/feeds/class-feed.php';

		foreach ( $this->feeds as $feed_slug ) {

			require_once CPR_PATH . "/migration/feeds/{$feed_slug}/class-feed.php";
			require_once CPR_PATH . "/migration/feeds/{$feed_slug}/class-feed-item.php";

			$feed_parts = explode( '-', $feed_slug );
			$feed_parts = array_map( 'ucfirst', $feed_parts );
			$feed_class = implode( '_', $feed_parts );
			\Alleypack\Sync_Script\register_feed( "\CPR\Migration\\{$feed_class}\Feed" );
		}
	}
}

// Initalize class.
add_action(
	'init',
	function() {
		Migration::instance();
	}
);
