<?php
/**
 * Class for parsing an NPR.
 *
 * @package CPR
 */

namespace CPR\Migration\NPR;

use function Alleypack\Sync_Script\alleypack_log;

/**
 * CPR Feed Item.
 */
class Feed_Item extends \Alleypack\Sync_Script\Post_Feed_Item {

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
	 * Map source data to the object.
	 */
	public function map_source_to_object() {

		$this->set_basics();
		$this->migrate_meta();

		// Body content. No block conversion needed for NPR.
		$this->object['post_content'] = $this->source['body']['und'][0]['value'] ?? '';
		if ( empty( $this->object['post_content'] ) ) {
			$this->object['post_content'] = $this->object['post_excerpt'];
		}

		// Log debug data.
		alleypack_log( 'Mapped source to object. Source:', $this->source );
		alleypack_log( 'Object:', $this->object );
	}

	/**
	 * Modify object after it's been saved.
	 *
	 * @return bool
	 */
	public function post_object_save() {
		update_post_meta( $this->get_object_id(), 'alleypack_sync_script_mapping_version', '2.0' );
		$this->set_section();
		$this->set_tags();
		$this->set_categories();

		/**
		 * NPR content.
		 */
		update_post_meta( $this->get_object_id(), 'npr_story_content', $this->object['post_content'] );

		/**
		 * NPR Bylines.
		 */
		$bylines = array_filter(
			array_map(
				function( $byline ) {
					return $byline['value'] ?? '';
				},
				$this->source['field_npr_byline']['und'] ?? []
			)
		);
		update_post_meta( $this->get_object_id(), 'bylines', $bylines );
		update_post_meta( $this->get_object_id(), 'npr_byline', $bylines[0] ?? '' );

		/**
		 * Legacy urls.
		 */
		$alias_path = $this->source['url_aliases'][0] ?? '';
		if ( ! empty( $alias_path ) ) {
			update_post_meta( $this->get_object_id(), 'legacy_path', $alias_path );
			update_post_meta( $this->get_object_id(), 'legacy_url', 'https://cpr.org/' . $alias_path );
		}

		/**
		 * NPR Audio.
		 */
		update_post_meta( $this->get_object_id(), 'audio_url', $this->source['npr_audio']['und'][0]['mp3'] ?? '' );
		update_post_meta( $this->get_object_id(), 'audio_title', $this->source['npr_audio']['und'][0]['title'] ?? '' );

		/**
		 * NPR Image.
		 */
		$filename = $this->source['field_npr_image']['und'][0]['uri'] ?? '';
		if ( ! empty( $filename ) ) {

			// Get the original file url.
			$filename = str_replace( 'public:///', '', $filename );
			$url      = 'https://old.cpr.org/sites/default/files/' . $filename;

			// Determine if it already exists.
			$query = new \WP_Query(
				[
					'post_type'   => 'attachment',
					'fields'      => 'ids',
					'post_status' => 'any',
					'meta_key'    => 'alleypack_attachments_legacy_url',
					'meta_value'  => $url,
				]
			);

			// Get or create attachment.
			$attachment_id = $query->posts[0] ?? 0;
			if ( 0 === $attachment_id ) {
				$attachment_id = \Alleypack\create_attachment_from_url(
					$url,
					[
						'alt'   => $this->source['field_npr_image']['und'][0]['alt'] ?? '',
						'title' => $this->source['field_npr_image']['und'][0]['title'] ?? '',
						'meta' => [
							'alleypack_attachments_legacy_url' => $url,
						],
					]
				);
			}

			if ( 0 !== $attachment_id ) {
				update_post_meta( $this->get_object_id(), '_thumbnail_id', $attachment_id );
				update_post_meta( $this->get_object_id(), 'featured_media_type', 'none' );

				$image_src = wp_get_attachment_url( $attachment_id );
				$alt       = $this->source['field_npr_image']['und'][0]['alt'] ?? '';

				$image = '<!-- wp:image -->'
					. PHP_EOL . '<div class="wp-block-image">'
					. PHP_EOL . '<figure class="alignright is-resized">'
					. PHP_EOL . '<img src="' . esc_url( $image_src ?? '' ) . '" alt="' . esc_attr( $alt ) . '" width="300"/>'
					. PHP_EOL . '</figure></div>'
					. PHP_EOL . '<!-- /wp:image -->';

				wp_update_post(
					[
						'ID'           => $this->get_object_id(),
						'post_content' => $image . $this->object['post_content'],
					]
				);
			}
		}

		/**
		 * NPR Meta.
		 */
		$npr_story_id = absint( $this->source['field_npr_id']['und'][0]['value'] ?? 0 );
		if ( ! empty( $npr_story_id ) ) {
			update_post_meta( $this->get_object_id(), 'npr_story_id', $npr_story_id );
			update_post_meta(
				$this->get_object_id(),
				'npr_api_link',
				'http://api.npr.org/query?id=' . $npr_story_id . '&apiKey=MDEyMDcxNjYwMDEzNzc2MTQzNDNiY2I3ZA004'
			);
		}

		wp_set_post_categories( $this->get_object_id(), [ 10762 ], false );

		/**
		 * Canonical url.
		 */
		update_post_meta( $this->get_object_id(), 'canonical_url', $this->source['field_npr_url']['und'][0]['value'] ?? '' );

		return true;
	}
}
