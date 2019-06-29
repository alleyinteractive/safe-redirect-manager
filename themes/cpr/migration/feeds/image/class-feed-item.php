<?php
/**
 * Class for parsing a image item.
 *
 * @package CPR
 */

namespace CPR\Migration\Image;

/**
 * CPR Feed Item.
 */
class Feed_Item extends \Alleypack\Sync_Script\Attachment_Feed_Item {

	use \CPR\Migration\Traits\Story;

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
	 * Get the source URL for this attachment.
	 *
	 * @return null|string
	 */
	public function get_source_url() : ?string {

		// Get filename.
		$filename = $this->source['field_photo']['und'][0]['uri'] ?? '';
		if ( empty( $filename ) ) {
			return null;
		}

		// Remove protocol.
		$filename = str_replace( 'public://', '', $filename );

		return 'https://www.cpr.org/sites/default/files/' . $filename;
	}

	/**
	 * Map source data to the object.
	 */
	public function map_source_to_object() {

		self::$mapping_version = '2.0';

		$this->object['post_title']   = esc_html( $this->source['title'] ?? '' );
		$this->object['post_excerpt'] = esc_html( $this->source['body']['und'][0]['value'] ?? '' );
		$this->object['post_content'] = $this->source['body']['und'][0]['value'] ?? '';
		$this->object['meta_input']   = [
			'credit'                       => $this->source['field_photo_credits']['und'][0]['value'] ?? '',
			'cpr_block_conversion_mapping' => \CPR\Migration\Content\Feed_Item::get_mapping_version(),
			'legacy_path'                  => str_replace( 'https://www.cpr.org', '', $this->get_source_url() ),
			'legacy_changed'               => $this->source['changed'] ?? '',
			'legacy_created'               => $this->source['created'] ?? '',
			'legacy_id'                    => $this->source['nid'],
			'legacy_path'                  => $this->source['path']['alias'] ?? '',
			'legacy_type'                  => $this->source['type'] ?? '',
		];
	}

	/**
	 * Get the source URL.
	 *
	 * @param mixed $source array Source object.
	 * @return null|string
	 */
	public static function get_url_from_source( $source ) : ?string {

		// Get filename.
		$filename = $source['field_photo']['und'][0]['uri'] ?? '';
		if ( empty( $filename ) ) {
			return null;
		}

		// Remove protocol.
		$filename = str_replace( 'public://', '', $filename );

		return 'https://www.cpr.org/sites/default/files/' . $filename;
	}
}
