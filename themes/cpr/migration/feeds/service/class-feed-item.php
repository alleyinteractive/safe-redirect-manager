<?php
/**
 * Class for parsing a service item.
 *
 * @package CPR
 */

namespace CPR\Migration\Service;

/**
 * CPR Feed Item.
 */
class Feed_Item extends \Alleypack\Sync_Script\Term_Feed_Item {

	/**
	 * Object Taxonomy.
	 *
	 * @var string
	 */
	public static $taxonomy = 'section';

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

		// Name.
		$this->object['name'] = $this->source['title'];

		// Slug if it exists.
		if ( ! empty( $this->source['path']['alias'] ) ) {
			$this->object['slug'] = $this->source['path']['alias'];
		}
	}
}
