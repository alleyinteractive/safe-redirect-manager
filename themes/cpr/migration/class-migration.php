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
		'album'                => true,
		'artist'               => false, // Migrated with albums.
		'blog-post'            => true,
		'category'             => true,
		'content'              => true,
		'document'             => true,
		'entry'                => true,
		'guest-author'         => true,
		'image'                => true,
		'job'                  => true,
		'label'                => false, // Migrated with albums.
		'newsletter'           => true,
		'page'                 => true,
		'podcast'              => true,
		'podcast-episode'      => true,
		'post-tag'             => true,
		'press-release'        => true,
		'service'              => true,
		'show'                 => true,
		'show-episode'         => true,
		'show-segment'         => true,
		'story'                => true,
		'top-30'               => true,
		'underwriter'          => true,
		'underwriter-category' => false, // Migrated with underwriters.
		'user'                 => true,
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

		require_once CPR_PATH . '/migration/traits/trait-story.php';

		$this->load_and_register_feeds();

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			require_once CPR_PATH . '/migration/cli/class-cleanup.php';
			require_once CPR_PATH . '/migration/cli/class-menus.php';
		}

		// Add additional sync link for content.
		add_filter(
			'post_row_actions',
			function( $actions, $post ) {

				// Only apply to some post types.
				if ( ! in_array( $post->post_type, Content\Feed_Item::$post_type, true ) ) {
					return $actions;
				}

				// Build url args.
				$args = [
					'post_id' => $post->ID,
				];

				// Add the current url as a redirect arg.
				if ( isset( $_SERVER['REQUEST_URI'] ) ) {
					$request_uri = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL );
					$args['redirect_to'] = rawurlencode( site_url( wp_unslash( $request_uri ) ) );
				}

				// Add a sync button to every row action.
				$actions['sync-content'] = sprintf(
					'<a href="%1$s">Sync Content</a>',
					esc_url(
						add_query_arg(
							$args,
							rest_url( 'alleypack/v2/sync/content/' )
						)
					)
				);

				return $actions;
			},
			10,
			2
		);
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
	 * @param string    $slug           Slug for directory to data.
	 * @param int       $offset         File offset.
	 * @param callaable $mapping_filter Callback used to modify the mapping
	 *                                  built being loaded.
	 * @return array
	 */
	public function get_source_data_by_offset( string $slug, int $offset, $mapping_filter = null ) : array {

		if ( empty( $this->data_mapping[ $slug ] ) ) {
			$this->build_mapping( $slug, $mapping_filter );
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
	 * @param string    $slug           Slug for directory to data.
	 * @param callaable $mapping_filter Callback used to modify the mapping
	 *                                  built being loaded.
	 */
	public function build_mapping( $slug, $mapping_filter = null ) {

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

			if ( is_callable( $mapping_filter ) ) {
				$files = call_user_func( $mapping_filter, $files );
			}

			$this->data_mapping = $files;
		}
	}

	/**
	 * Load feed files and register.
	 */
	public function load_and_register_feeds() {

		// Generic feed since all our data is structured the same way.
		require_once CPR_PATH . '/migration/feeds/class-feed.php';
		require_once CPR_PATH . '/migration/feeds/class-post-datasource-feed.php';

		foreach ( $this->feeds as $feed_slug => $register ) {

			require_once CPR_PATH . "/migration/feeds/{$feed_slug}/class-feed.php";
			require_once CPR_PATH . "/migration/feeds/{$feed_slug}/class-feed-item.php";

			if ( $register ) {
				$feed_parts = explode( '-', $feed_slug );
				$feed_parts = array_map( 'ucfirst', $feed_parts );
				$feed_class = implode( '_', $feed_parts );
				\Alleypack\Sync_Script\register_feed( "\CPR\Migration\\{$feed_class}\Feed" );
			}
		}
	}

	/**
	 * Get the source URL for this attachment.
	 *
	 * @param string $uri URI for file.
	 * @return null|string
	 */
	public static function get_url_from_uri( string $uri ) : ?string {

		// Validate URI..
		if ( empty( $uri ) ) {
			return null;
		}

		// Remove protocol.
		$uri = str_replace( 'public://', '', $uri );

		return 'https://www.cpr.org/sites/default/files/' . $uri;
	}
}

// Initalize class.
add_action(
	'init',
	function() {
		Migration::instance();
	}
);
