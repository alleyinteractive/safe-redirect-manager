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

	use \CPR\Migration\Traits\Story;

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
		if ( 0 === $story_type_id ) {
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

		$this->set_basics();

		// Map meta.
		$this->object['meta_input'] = [
			'legacy_id'      => $this->source['nid'],
			'template'       => sanitize_title( $this->source['title'] ?? '' ),
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
		$this->migrate_bylines();
		$this->migrate_featured_image();
		$this->set_section();
		$this->set_podcast();
		$this->set_tags();
		$this->set_categories();
		return true;
	}
}
