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

	public function __construct() {

		// Load the sync script.
		\Alleypack\load_module( 'sync-script', '1.1' );

		// Migration.
		require_once CPR_PATH . '/migration/story/class-feed.php';
		require_once CPR_PATH . '/migration/story/class-feed-item.php';
		\Alleypack\Sync_Script\register_feed( '\CPR\Migration\Story\Feed' );

	}

	/**
	 * Return an array of all the JSON files in a migration data directory.
	 *
	 * @param string $slug Slug of data object.
	 * @return array
	 */
	public static function get_directory_json( $slug ) {
		$directory = get_template_directory() . "/migration/{$slug}/data/";
		$files     = scandir( $directory );

		if ( empty( $files ) ) {
			return [];
		}

		// Remove directories.
		$files = array_values(
			array_filter(
				array_map(
					function( $file ) {
						if ( false !== strpos( $file, '.json' ) ) {
							return $file;
						}
					},
					$files
				)
			)
		);

		return $files;
	}

	/**
	 * Load a JSON file.
	 *
	 * @param int    $id   ID of object.
	 * @param string $slug Slug of object type.
	 * @return array
	 */
	public static function load_json_data( $id, $slug ) {
		$file = get_template_directory() . "/migration/{$slug}/data/{$id}.json";
		if ( ! file_exists( $file ) ) {
			return [];
		}

		$json_string = file_get_contents( $file );
		return json_decode( $json_string, true );
	}
}

// Initalize class.
add_action(
	'init',
	function() {
		new Migration();
	}
);
