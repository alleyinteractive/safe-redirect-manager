<?php
/**
 * Class for parsing a Newsletter.
 *
 * @package CPR
 */

namespace CPR\Migration\Newsletter;

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
	public static $post_type = 'newsletter-single';

	/**
	 * This object should always sync.
	 *
	 * @return bool
	 */
	public function should_object_sync() : bool {

		/**
		 * Modify migration for newsletters.
		 */
		$tag_ids = array_filter(
			array_map(
				function( $und ) {
					return absint( $und['tid'] ?? 0 );
				},
				$this->source['field_tags']['und'] ?? []
			)
		);

		if ( ! in_array( 9727, $tag_ids, true ) ) {
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
		$this->global_post_save();
		update_post_meta( $this->get_object_id(), 'newsletter_html', $this->source['body']['und'][0]['value'] ?? '' );
		wp_set_object_terms( $this->get_object_id(), 'The Lookout', 'newsletter' );
		return true;
	}
}
