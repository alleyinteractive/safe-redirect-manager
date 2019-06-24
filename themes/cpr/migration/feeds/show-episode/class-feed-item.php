<?php
/**
 * Class for parsing a Show Episode.
 *
 * @package CPR
 */

namespace CPR\Migration\Show_Episode;

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
	public static $post_type = 'show-episode';

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
		$this->migrate_audio_files();
		$this->migrate_bylines();
		$this->migrate_featured_image();
		$this->set_section();
		$this->set_show();
		$this->set_tags();
		$this->set_categories();
		return true;
	}
}
