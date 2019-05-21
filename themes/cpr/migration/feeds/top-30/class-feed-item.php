<?php
/**
 * Class for parsing a top 30 item.
 *
 * @package CPR
 */

namespace CPR\Migration\Top_30;

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
	public static $post_type = 'top-30';

	/**
	 * This object should always sync.
	 *
	 * @return bool
	 */
	public function should_object_sync() : bool {

		// This is actually a blog post, so ignore.
		if ( empty( $this->source['field_top_30_list'] ?? [] ) ) {
			return false;
		}

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

		// Log debug data.
		alleypack_log( 'Mapped source to object. Source:', $this->source );
		alleypack_log( 'Object:', $this->object );
	}

	/**
	 * Modify object after it's been saved.
	 *
	 * @return bool
	 */
	public function post_object_save() {
		$this->migrate_music();
		return true;
	}

	/**
	 * Loop through all music ids and migrate to albums.
	 */
	public function migrate_music() {

		// Extract the original ids.
		$music_ids = empty( $this->source['field_top_30_list']['und'] )
			? []
			: wp_list_pluck( $this->source['field_top_30_list']['und'], 'target_id' );

		// Map original ids to new ids.
		$album_ids = array_map(
			function( $music_id ) {
				$source = \CPR\Migration\Migration::instance()->get_source_data_by_id( 'music', $music_id );
				return \CPR\Migration\Album\Feed_Item::get_or_create_object_from_source( $source )->ID ?? 0;
			},
			$music_ids
		);

		// Set albums as meta.
		update_post_meta( $this->get_object_id(), 'album_ids', $album_ids );
	}
}
