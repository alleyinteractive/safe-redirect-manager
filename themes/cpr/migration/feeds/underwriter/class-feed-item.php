<?php
/**
 * Class for parsing a underwriter item.
 *
 * @package CPR
 */

namespace CPR\Migration\Underwriter;

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
	public static $post_type = 'underwriter';

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

		$this->object['post_status'] = 'publish';
		$this->object['post_title']  = esc_html( $this->source['title'] );
		$this->object['meta_input']  = [
			// 'address'              => $this->source['field_phone_number']['und'][0]['safe_value'] ?? '',
			'is_corporate_partner' => $this->source['field_corporate_partner']['und'][0]['value'] ?? '0',
			'link'                 => $this->source['field_website_url']['und'][0]['url'] ?? '',
			'phone_number'         => $this->source['field_phone_number']['und'][0]['safe_value'] ?? '',
		];
	}
}
