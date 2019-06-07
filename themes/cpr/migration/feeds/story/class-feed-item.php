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

		/**
		 * Modify migration for story types.
		 */
		$story_type_id = absint( $this->source['field_story_type']['und'][0]['tid'] ?? 0 );
		if ( 0 !== $story_type_id ) {

			// Is this source actually a podcast episode?
			$podcast_unique_ids = \CPR\Migration\Podcast\Feed::$unique_taxonomy_ids_for_podcasts;
			if ( in_array( $story_type_id, $podcast_unique_ids, true ) ) {

				// Migrate that instead and skip over the object.
				$podcast_episode = \CPR\Migration\Podcast_Episode\Feed_Item::get_or_create_object_from_source( $this->source );
				return false;
			}
		}

		/**
		 * Modify migration for newsletters.
		 */
		$tag_ids = array_filter(
			array_map(
				function( $und ) {
					return absint( $und['tid'] ?? 0 );
				},
				$this->source['field_tags']['und'] ?? []
			)
		);
		
		if ( in_array( 9727, $tag_ids, true ) ) {
			$newsletter = \CPR\Migration\Newsletter\Feed_Item::get_or_create_object_from_source( $this->source );
			if ( $newsletter instanceof \WP_Post ) {
				wp_update_post(
					[
						'ID'        => $newsletter->ID,
						'post_type' => 'newsletter-single',
					]
				);
			}
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
}
