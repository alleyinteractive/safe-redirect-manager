<?php
/**
 * Class for parsing a guest-author item.
 *
 * @package CPR
 */

namespace CPR\Migration\Guest_Author;

use Alleypack\Block\Converter;
use function Alleypack\Sync_Script\alleypack_log;

/**
 * CPR Feed Item.
 */
class Feed_Item extends \Alleypack\Sync_Script\Guest_Author_Feed_Item {

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
		$this->object['display_name'] = $this->source['title'];
		$this->object['user_login']   = sanitize_title( $this->source['title'] );
	}

	/**
	 * Modify object after it's been saved.
	 *
	 * @return bool
	 */
	public function post_object_save() : bool {
		$post_id = $this->get_object_id();

		if ( is_null( $post_id ) ) {
			return false;
		}

		update_post_meta( $post_id, 'cap-first_name', $this->source['field_first_name']['und'][0]['value'] ?? '' );
		update_post_meta( $post_id, 'cap-last_name', $this->source['field_last_name']['und'][0]['value'] ?? '' );

		return true;
	}
}
