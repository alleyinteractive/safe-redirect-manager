<?php
/**
 * Class for parsing a job item.
 *
 * @package CPR
 */

namespace CPR\Migration\Job;

use Alleypack\Block\Converter;
use function Alleypack\Sync_Script\alleypack_log;

/**
 * CPR Feed Item.
 */
class Feed_Item extends \Alleypack\Sync_Script\Post_Feed_Item {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	public static $post_type = 'job';

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
		$this->object['post_content'] = $this->source['body']['und'][0]['value'] ?? '';
		$this->object['post_status']  = 'publish';
		$this->object['post_title']   = esc_html( $this->source['title'] );
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

		return true;
	}
}
