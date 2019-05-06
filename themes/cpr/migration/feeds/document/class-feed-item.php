<?php
/**
 * Class for parsing a document item.
 *
 * @package CPR
 */

namespace CPR\Migration\Document;

/**
 * CPR Feed Item.
 */
class Feed_Item extends \Alleypack\Sync_Script\Attachment_Feed_Item {

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
		$filename = $this->source['field_file']['und'][0]['filename'] ?? '';

		if ( empty( $filename ) ) {
			return null;
		}

		return 'https://www.cpr.org/sites/default/files/' . $filename;
	}

	/**
	 * Map source data to the object.
	 */
	public function map_source_to_object() {
		$this->object['post_title']  = esc_html( $this->source['title'] );
	}
}
