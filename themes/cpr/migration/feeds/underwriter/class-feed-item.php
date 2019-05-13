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

	/**
	 * Callback after the object has been successfully created.
	 */
	public function post_object_save() {
		$this->migrate_logo();
	}

	/**
	 * Download and set the logo as the featured image.
	 */
	public function migrate_logo() {

		// Logo has already been migrated, or doesn't exist.
		if (
			has_post_thumbnail( $this->get_object_id() )
			|| empty( $this->source['field_logo']['und'][0]['filename'] ?? '' )
		) {
			return;
		}


		$attachment_id = \Alleypack\create_attachment_from_url( $this->get_logo_url() );
		if ( ! $attachment_id instanceof \WP_Post ) {
			update_post_meta( $this->get_object_id(), '_thumbnail_id', $attachment_id );
		}
	}

	/**
	 * Return the logo url for this underwriter.
	 *
	 * @return string
	 */
	public function get_logo_url() {

		// Get filename.
		$filename = $this->source['field_logo']['und'][0]['filename'] ?? '';
		if ( empty( $filename ) ) {
			return null;
		}

		return 'https://www.cpr.org/sites/default/files/styles/underwriter_logo/public/' . $filename;
	}
}
