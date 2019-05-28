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
			'address'              => $this->get_address(),
			'is_corporate_partner' => $this->source['field_corporate_partner']['und'][0]['value'] ?? '0',
			'link'                 => $this->source['field_website_url']['und'][0]['url'] ?? '',
			'phone_number'         => $this->source['field_phone_number']['und'][0]['safe_value'] ?? '',
		];
	}

	/**
	 * Get the fully formed address.
	 *
	 * @return string
	 */
	public function get_address() {

		// Ensure we have an address.
		if ( empty( $this->source['field_address']['und'][0] ) ) {
			return '';
		}

		$address = $this->source['field_address']['und'][0];

		if ( empty( $address['thoroughfare'] ) ) {
			return '';
		}

		return <<<EOT
{$address['thoroughfare']}
{$address['locality']} {$address['administrative_area']}, {$address['postal_code']}
EOT;
	}

	/**
	 * Callback after the object has been successfully created.
	 */
	public function post_object_save() {
		$this->migrate_logo();
		$this->migrate_categories();
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

	/**
	 * Migrate underwriter categories.
	 */
	public function migrate_categories() {

		// Extract legacy ids.
		$tids = wp_list_pluck( ( $this->source['field_service_categories']['und'] ?? [] ), 'tid' );

		// Create a term for each one.
		$term_ids = array_filter( array_map(
			function( $tid ) {
				$source = \CPR\Migration\Migration::instance()->get_source_data_by_id( 'taxonomy', $tid );
				return \CPR\Migration\Underwriter_Category\Feed_Item::get_or_create_object_from_source( $source )->term_id ?? 0;
			},
			$tids
		) );

		if ( ! empty( $term_ids ) ) {
			wp_set_object_terms( $this->get_object_id(), $term_ids, 'underwriter-category' );
		}
	}
}
