<?php
/**
 * Class for parsing a story item.
 *
 * @package CPR
 */

namespace CPR\Migration\Story;

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

		if ( $this->migrate_episodes() ) {
			return false;
		}

		if ( $this->migrate_newsletters() ) {
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

		$this->set_basics();
		$this->migrate_meta();

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
		$this->migrate_bylines();
		$this->migrate_featured_image();
		$this->set_section();
		$this->set_podcast();
		$this->set_tags();
		$this->set_categories();
		return true;
	}

	/**
	 * Hijack newsletter stories and migrate them as newsletter-single posts.
	 *
	 * @return bool Was this actually a newsletter?
	 */
	public function migrate_episodes() : bool {

		// Make sure we have audio.
		if ( empty( $this->source['field_audio']['und'][0]['target_id'] ) ) {
			return false;
		}

		// Parse tag ids.
		$story_type_ids = array_filter(
			array_map(
				function( $und ) {
					return absint( $und['tid'] ?? 0 );
				},
				$this->source['field_story_type']['und'] ?? []
			)
		);

		// Are any of the story IDs for this in the list of podcast terms?
		$is_podcast = ! empty(
			array_intersect(
				$story_type_ids,
				\CPR\Migration\Podcast\Feed::$unique_taxonomy_ids_for_podcasts
			)
		);

		if ( $is_podcast ) {
			return $this->migrate_podcast_episode();
		}

		// Are any of the story IDs for this in the list of podcast terms?
		$is_show = ! empty(
			array_intersect(
				$story_type_ids,
				\CPR\Migration\Show\Feed::$unique_taxonomy_ids_for_shows
			)
		);

		if ( $is_show ) {
			return $this->migrate_show_episode();
		}


		return false;
	}

	/**
	 * Migrate this item as a podcast episode.
	 *
	 * @return bool
	 */
	public function migrate_podcast_episode() : bool {

		$episode = \CPR\Migration\Podcast_Episode\Feed_Item::get_or_create_object_from_source( $this->source );

		// Fix the post type.
		if ( $episode instanceof \WP_Post ) {
			wp_update_post(
				[
					'ID'        => $episode->ID,
					'post_type' => 'podcast-episode',
				]
			);
		}

		// Success!
		return true;
	}

	/**
	 * Migrate this item as a show episode.
	 *
	 * @return bool
	 */
	public function migrate_show_episode() : bool {

		$episode = \CPR\Migration\Show_Episode\Feed_Item::get_or_create_object_from_source( $this->source );

		// Fix the post type.
		if ( $episode instanceof \WP_Post ) {
			wp_update_post(
				[
					'ID'        => $episode->ID,
					'post_type' => 'show-episode',
				]
			);
		}

		// Success!
		return true;
	}

	/**
	 * Hijack newsletter stories and migrate them as newsletter-single posts.
	 *
	 * @return bool Was this actually a newsletter?
	 */
	public function migrate_newsletters() : bool {

		// Parse tag ids.
		$tag_ids = array_filter(
			array_map(
				function( $und ) {
					return absint( $und['tid'] ?? 0 );
				},
				$this->source['field_tags']['und'] ?? []
			)
		);

		// Is this tagged as a newsletter?
		if ( in_array( 9727, $tag_ids, true ) ) {

			// Migrate using the Newsletter feed.
			$newsletter = \CPR\Migration\Newsletter\Feed_Item::get_or_create_object_from_source( $this->source );

			// Fix the post type.
			if ( $newsletter instanceof \WP_Post ) {
				wp_update_post(
					[
						'ID'        => $newsletter->ID,
						'post_type' => 'newsletter-single',
					]
				);
			}

			// Success!
			return true;
		}

		return false;
	}
}
