<?php
/**
 * Class for parsing a podcast term.
 *
 * @package CPR
 */

namespace CPR\Migration\Podcast;

/**
 * CPR Feed Item.
 */
class Feed_Item extends \Alleypack\Sync_Script\Term_Feed_Item {

	/**
	 * Object Taxonomy.
	 *
	 * @var string
	 */
	public static $taxonomy = 'podcast';

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
		return $this->source['tid'] ?? false;
	}

	/**
	 * Map source data to the object.
	 */
	public function map_source_to_object() {

		// Name.
		$this->object['name'] = $this->source['name'];
	}
}
