<?php
/**
 * Class for parsing a Podcast Episode.
 *
 * @package CPR
 */

namespace CPR\Migration\Podcast_Episode;

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
	public static $post_type = 'podcast-episode';

	/**
	 * This object should always sync.
	 *
	 * @return bool
	 */
	public function should_object_sync() : bool {

		// Get this object's story type id.
		$story_type_id = absint( $this->source['field_story_type']['und'][0]['tid'] ?? 0 );
		if ( 0 === $story_type_id) {
			return false;
		}

		// Is this a podcast episode?
		$podcast_unique_ids = \CPR\Migration\Podcast\Feed::$unique_taxonomy_ids_for_podcasts;
		if ( ! in_array( $story_type_id, $podcast_unique_ids, true ) ) {
			return false;
		}

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
	 * Modify object after it's been saved.
	 *
	 * @return bool
	 */
	public function post_object_save() {

		// Setup bylines.
		$legacy_guest_author_ids = [ 0 ]; // This will fallback to the default user.
		if ( ! empty( $this->source['field_author']['und'] ?? [] ) ) {
			$legacy_guest_author_ids = wp_list_pluck( $this->source['field_author']['und'], 'target_id' );
		}

		// Set the byline.
		\CPR\Migration\Guest_Author\Feed::set_bylines(
			$this->get_object_id(),
			$legacy_guest_author_ids
		);

		// Set the featured image.
		\CPR\Migration\Image\Feed::set_featured_image(
			$this->get_object_id(),
			( $this->source['field_feature_image']['und'][0]['target_id'] ?? 0 )
		);

		// Set the section.
		\CPR\Migration\Service\Feed::set_section(
			$this->get_object_id(),
			( $this->source['field_primary_service']['und'][0]['target_id'] ?? 0 )
		);

		// Set the tags.
		if ( ! empty( $this->source['field_tags']['und'] ?? [] ) ) {
			\CPR\Migration\Post_Tag\Feed::set_tags(
				$this->get_object_id(),
				wp_list_pluck( $this->source['field_tags']['und'], 'tid' )
			);
		}

		// Set the categories.
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

		// Set the podcast.
		\CPR\Migration\Podcast\Feed::set_podcast(
			$this->get_object_id(),
			( $this->source['field_story_type']['und'][0]['tid'] ?? 0 )
		);

		return true;
	}
}
