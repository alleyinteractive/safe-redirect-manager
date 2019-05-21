<?php
/**
 * Class for parsing an album item.
 *
 * @package CPR
 */

namespace CPR\Migration\Album;

use function Alleypack\Sync_Script\alleypack_log;

/**
 * CPR Feed Item.
 */
class Feed_Item extends \Alleypack\Sync_Script\Post_Feed_Item {

	use \CPR\Migration\Traits\Story;

	/**
	 * Post type.
	 *
	 * @var string
	 */
	public static $post_type = 'album';

	/**
	 * This object should always sync.
	 *
	 * @return bool
	 */
	public function should_object_sync() : bool {
		return true;
	}

	/**
	 * Get a unique id that will be used to associate the source data to the
	 * saved object.
	 *
	 * @return string|bool
	 */
	public function get_unique_id() {
		return $this->source['nid'] ?? false;
	}

	/**
	 * Map source data to the object.
	 */
	public function map_source_to_object() {
		$this->set_basics();
		$this->migrate_meta();
	}

	/**
	 * Modify object after it's been saved.
	 *
	 * @return bool
	 */
	public function post_object_save() {
		$this->migrate_artwork();
		return true;
	}

	/**
	 * Download and set the artwork as the featured image.
	 */
	public function migrate_artwork() {

		// Logo has already been migrated, or doesn't exist.
		if (
			has_post_thumbnail( $this->get_object_id() )
			|| empty( $this->source['field_album_artwork']['und'][0]['filename'] ?? '' )
		) {
			return;
		}

		$attachment_id = \Alleypack\create_attachment_from_url( $this->get_artwork_url() );
		if ( ! $attachment_id instanceof \WP_Error ) {
			update_post_meta( $this->get_object_id(), '_thumbnail_id', $attachment_id );
		}
	}

	/**
	 * Return the artwork url for this underwriter.
	 *
	 * @return string
	 */
	public function get_artwork_url() {

		// Get filename.
		$filename = $this->source['field_album_artwork']['und'][0]['filename'] ?? '';
		if ( empty( $filename ) ) {
			return null;
		}

		return 'https://www.cpr.org/sites/default/files/styles/cover/public/album_art/' . $filename;
	}
}
