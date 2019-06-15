<?php
/**
 * Feed for migrating Podcasts.
 *
 * @package CPR
 */

namespace CPR\Migration\Podcast;

/**
 * Feed.
 */
class Feed extends \CPR\Migration\Feed {

	// Enable functionality for this feed.
	use \Alleypack\Sync_Script\Endpoint;
	use \Alleypack\Sync_Script\GUI;

	/**
	 * Legacy slug for this feed.
	 *
	 * @var string
	 */
	protected $data_slug = 'taxonomy';

	/**
	 * A unique slug for this feed.
	 *
	 * @var string
	 */
	protected $sync_slug = 'podcast';

	/**
	 * Define the feed item to sync.
	 *
	 * @var string
	 */
	protected $feed_item_class = '\CPR\Migration\Podcast\Feed_Item';

	/**
	 * The unique ids for the podcast taxonomy objects.
	 *
	 * @var array
	 */
	public static $unique_taxonomy_ids_for_podcasts = [
		9557, // Purplish.
		9620, // The Playlist League.
		8666, // Centennial Sounds.
		7862, // Inside Track.
		7790, // OpenAir Sessions Podcast.
		9084, // The Taxman.
		8934, // The Great Composers.
		6532, // The Beethoven 9.
		7524, // The Beethoven 9 Podcast.
		9410, // Who's Gonna Govern.
		5734, // Colorado Art Report.
	];

	/**
	 * Callback used to modify the mapping built being loaded.
	 *
	 * @param array $mapping Mapping.
	 * @return array
	 */
	public function mapping_filter( array $mapping ) : array {
		return self::$unique_taxonomy_ids_for_podcasts;
	}

	/**
	 * Helper to set the podcast for a given post.
	 *
	 * @param int        $post_id           Post id.
	 * @param string|int $podcast_unique_id Unique (legacy) id for this
	 *                                      service/podcast.
	 */
	public static function set_podcast( int $post_id, $podcast_unique_id ) {

		// Attempt to retreieve the podcast term.
		$podcast_term = \CPR\Migration\Podcast\Feed_Item::get_object_by_unique_id( $podcast_unique_id );

		// Validate and set.
		if ( $podcast_term instanceof \WP_Term ) {
			wp_set_object_terms(
				$post_id,
				$podcast_term->term_id,
				'podcast'
			);
		}
	}
}
