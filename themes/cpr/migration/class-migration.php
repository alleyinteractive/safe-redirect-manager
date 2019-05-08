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
	public $migration_scope = 'partial';

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

		// Load some AlleyPack modules.
		\Alleypack\load_module( 'attachments', '1.0' );
		\Alleypack\load_module( 'block-converter', '1.0' );
		\Alleypack\load_module( 'sync-script', '1.2' );

		$this->load_and_register_feeds();
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

	public function get_or_migrate_object( $feed_item_class, $slug, $id ) {


		$source = $this->get_source_data_by_id( $slug, $id );
		print_r($source); die();

	}

	// public function get_or_migate_image( $legacy_id ) {


	// 	$thing = \CPR\Migration\Migration::instance()->get_source_data_by_id( 'image', $legacy_featured_image );
	// 	print_r($thing); die();

	// 	// echo $legacy_featured_image;
	// 	// echo 'here';
	// 	// echo legacy_featured_image;
	// 	$object = \CPR\Migration\Image\Feed_Item::get_object_by_unique_id( $legacy_featured_image );
	// 	if ( empty( $object ) ) {
	// 		$image = new \CPR\Migration\Image\Feed_Item();
	// 		$image->load_object();

	// 		// load_source
	// 		//
	// 		//
	// 	}
	// 	echo 'yo';
	// 	print_r($object);
	// 	echo 'yo2';
	// 	die();

	// 	// if ( ! has_post_thumbnail )

	// 	// Set the featured image.
	// 	// \CPR\Migration\Image\Feed::set_featured_image(
	// 	// 	$this->get_object_id(),
	// 	// 	( $this->source['field_feature_image']['und'][0]['target_id'] ?? 0 )
	// 	// );
	// 	//
	// 	// get the attachment by image
	// 	// if it exists, save as featured
	// 	// 	if not, create, migrate, and move on


	// }
}

// Initalize class.
add_action(
	'init',
	function() {
		Migration::instance();
	}
);
