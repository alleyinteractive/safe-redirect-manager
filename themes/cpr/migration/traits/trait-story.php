<?php
/**
 * Trait with story content type helpers.
 *
 * @package CPR
 */

namespace CPR\Migration\Traits;

/**
 * Story trait.
 */
trait Story {

	/**
	 * Setup the basic values for the object.
	 */
	public function set_basics() {

		// Map fields.
		$this->object['post_name']    = sanitize_title( $this->source['title'] ?? '' );
		$this->object['post_title']   = wp_strip_all_tags( $this->source['title'] ?? '' );
		$this->object['post_excerpt'] = wp_strip_all_tags( $this->source['body']['und'][0]['summary'] ?? '' );
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
	}

	/**
	 * Map predictable meta for story types.
	 */
	public function migrate_meta() {

		// If the featured image is in the body content, hide the featured image.
		$legacy_image_id = $this->source['field_feature_image']['und'][0]['target_id'] ?? 0;
		if (
			! empty( $legacy_image_id )
			&& false !== strpos(
				( $this->source['body']['und'][0]['value'] ?? '' ),
				"[[nid:{$legacy_image_id} "
			)
		) {
			update_post_meta( $this->get_object_id(), 'featured_media_type', 'none' );
		}

		// Map meta.
		$this->object['meta_input'] = [
			'author'         => $this->source['field_author']['und'][0]['target_id'] ?? 0,
			'featured_image' => $this->source['field_feature_image']['und'][0]['target_id'] ?? 0,
			'legacy_changed' => $this->source['changed'] ?? '',
			'legacy_created' => $this->source['created'] ?? '',
			'legacy_id'      => $this->source['nid'],
			'legacy_path'    => $this->source['path']['alias'] ?? '',
			'legacy_type'    => $this->source['type'] ?? '',
			'legacy_url'     => empty( $this->source['path']['alias'] ) ? '' : 'https://cpr.org/' . $this->source['path']['alias'],
			'template'       => sanitize_title( $this->source['title'] ?? '' ),
		];
	}

	/**
	 * Catch-all after an object has saved.
	 */
	public function global_post_save() {
		update_post_meta( $this->get_object_id(), 'alleypack_sync_script_mapping_version', \CPR\Migration\Migration_CLI::$version );
		delete_post_meta( $this->get_object_id(), '_legacy_post_content' );
	}

	/**
	 * Migrate bylines from the field_author field.
	 */
	public function migrate_bylines() {

		// This will fallback to the default user.
		$legacy_guest_author_ids = [ 0 ];

		// Attempt to extract actual bylines.
		if ( ! empty( $this->source['field_author']['und'] ?? [] ) ) {
			$legacy_guest_author_ids = wp_list_pluck( $this->source['field_author']['und'], 'target_id' );
		}

		// Set the byline(s).
		\CPR\Migration\Guest_Author\Feed::set_bylines(
			$this->get_object_id(),
			$legacy_guest_author_ids
		);
	}

	/**
	 * Migrate the audio.
	 */
	public function migrate_audio_files() {

		// Get the audio nid and validate.
		$audio_nid = absint( $this->source['field_audio']['und'][0]['target_id'] ?? 0 );
		if ( 0 === $audio_nid ) {
			return;
		}

		// Get or create the attachments.
		$source     = \CPR\Migration\Migration::instance()->get_source_data_by_id( 'audio', $audio_nid );
		$audio_item = new \CPR\Migration\Audio\Feed_Item();
		$audio_item->load_source( $source );
		$audio_item->sync();

		// Store each ID.
		foreach ( (array) $audio_item->object as $key => $value ) {
			update_post_meta( $this->get_object_id(), $key, $value );
		}
	}
	/**
	 * Migrate the featured image.
	 */
	public function migrate_featured_image() {
		\CPR\Migration\Image\Feed::set_featured_image(
			$this->get_object_id(),
			( $this->source['field_feature_image']['und'][0]['target_id'] ?? 0 )
		);
	}

	/**
	 * Set the section term.
	 */
	public function set_section() {
		\CPR\Migration\Service\Feed::set_section(
			$this->get_object_id(),
			( $this->source['field_primary_service']['und'][0]['target_id'] ?? 0 )
		);
	}

	/**
	 * Set the podcast term.
	 */
	public function set_podcast() {
		\CPR\Migration\Podcast\Feed::set_podcast(
			$this->get_object_id(),
			( $this->source['field_story_type']['und'][0]['tid'] ?? 0 )
		);

	}

	/**
	 * Set the show term.
	 */
	public function set_show() {
		\CPR\Migration\Show\Feed::set_show(
			$this->get_object_id(),
			( $this->source['field_story_type']['und'][0]['tid'] ?? 0 )
		);
	}

	/**
	 * Set the tags.
	 */
	public function set_tags() {
		if ( ! empty( $this->source['field_tags']['und'] ?? [] ) ) {
			\CPR\Migration\Post_Tag\Feed::set_tags(
				$this->get_object_id(),
				wp_list_pluck( $this->source['field_tags']['und'], 'tid' )
			);
		}
	}

	/**
	 * Set the categories.
	 */
	public function set_categories() {
		if ( ! empty( $this->source['field_topics']['und'] ?? [] ) ) {
			\CPR\Migration\Category\Feed::set_categories(
				$this->get_object_id(),
				wp_list_pluck( $this->source['field_topics']['und'], 'target_id' )
			);
		} else {
			$categories = get_the_category( $this->get_object_id() );
			if ( ! empty( $categories ) ) {
				wp_remove_object_terms(
					$this->get_object_id(),
					wp_list_pluck( $categories, 'term_id' ),
					'category'
				);
			}
		}
	}
}
