<?php
/**
 * Class for parsing a user.
 *
 * @package CPR
 */

namespace CPR\Migration\User;

use Alleypack\Block\Converter;
use function Alleypack\Sync_Script\alleypack_log;

/**
 * CPR Feed Item.
 */
class Feed_Item extends \Alleypack\Sync_Script\User_Feed_Item {

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
		return $this->source['uid'] ?? false;
	}

	/**
	 * Map source data to the object.
	 */
	public function map_source_to_object() {

		$this->object['user_login']    = $this->source['name'];
		$this->object['user_nicename'] = $this->source['name'];
		$this->object['user_email']    = $this->source['mail'];

	}
}
