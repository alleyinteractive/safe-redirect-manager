<?php
/**
 * Class for parsing a story.
 *
 * @package CPR
 */

namespace CPR\Migration\Story;

/**
 * Feed Item.
 */
class Feed_Item extends \Alleypack\Sync_Script\Post_Feed_Item {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	public static $post_type = 'post';

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
		return '';
	}

	/**
	 * Map source data to the object.
	 */
	public function map_source_to_object() {
		// Begin mapping source to the object.
		print_r( $this->source );
		die();
	}

	/**
	 * Modify object after it's been saved.
	 */
	public function post_object_save() {
	}
}
