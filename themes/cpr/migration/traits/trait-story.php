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
	}

	/**
	 * Map predictable meta for story types.
	 */
	public function migrate_meta() {

		// Map meta.
		$this->object['meta_input'] = [
			'audio'          => $this->source['field_audio']['und'][0]['target_id'] ?? 0,
			'author'         => $this->source['field_author']['und'][0]['target_id'] ?? 0,
			'featured_image' => $this->source['field_feature_image']['und'][0]['target_id'] ?? 0,
			'legacy_changed' => $this->source['changed'] ?? '',
			'legacy_created' => $this->source['created'] ?? '',
			'legacy_id'      => $this->source['nid'],
			'legacy_path'    => $this->source['path']['alias'] ?? '',
			'legacy_type'    => $this->source['type'] ?? '',
			'legacy_url'     => 'https://cpr.org/' . $this->source['path']['alias'],
			'template'       => sanitize_title( $this->source['title'] ?? '' ),
		];
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
