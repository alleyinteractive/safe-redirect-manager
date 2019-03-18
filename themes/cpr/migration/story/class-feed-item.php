<?php
/**
 * Class for parsing a story.
 *
 * @package CPR
 */

namespace CPR\Migration\Story;

use function Alleypack\Sync_Script\alleypack_log;

/**
 * Feed Item.
 */
class Feed_Item extends \Alleypack\Sync_Script\Post_Feed_Item {

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

		// Map fields.
		$title                        = sanitize_title( $this->source['title'] ?? '' );
		$this->object['post_name']    = $title;
		$this->object['post_title']   = wp_strip_all_tags( $this->source['title'] ?? '' );
		$this->object['post_content'] = $this->source['body']['und'][0]['value'] ?? '';
		$this->object['post_status']  = 'publish';

		// Object date.
		$date = $this->source['created'] ?? '';
		if ( empty( $date ) ) {
			$date = time();
		}
		$this->object['post_date'] = date( 'Y-m-d H:i:s', $date );

		// Object Modified date.
		$modified_date = $this->source['changed'] ?? '';
		if ( empty( $modified_date ) ) {
			$date = time();
		}
		$this->object['post_modified'] = date( 'Y-m-d H:i:s', $modified_date );

		// Map meta.
		$this->object['meta_input'] = [
			'legacy_id'      => $this->source['nid'],
			'template'       => $title,
			'legacy_type'    => $this->source['type'] ?? '',
			'legacy_created' => $this->source['created'] ?? '',
			'legacy_changed' => $this->source['changed'] ?? '',
			'audio'          => $this->source['field_audio']['und'][0]['target_id'] ?? 0,
			'author'         => $this->source['field_author']['und'][0]['target_id'] ?? 0,
			'featured_image' => $this->source['field_feature_image']['und'][0]['target_id'] ?? 0,
		];

		// Log debug data.
		alleypack_log( 'Mapped source to object. Source:', $this->source );
		alleypack_log( 'Object:', $this->object );
	}

	/**
	 * Override method so that post statuses are left alone during sync.
	 */
	public static function mark_existing_content_as_syncing() {
	}

	/**
	 * Override method so that post statuses are left alone during sync.
	 */
	public static function unpublish_unsynced_content() {
	}
}
