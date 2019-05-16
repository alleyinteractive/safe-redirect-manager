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
	public $unique_taxonomy_ids_for_podcasts = [
		9557, // Purplish
		9620, // The Playlist League
		8666, // Centennial Sounds
		7862, // Inside Track
		7790, // OpenAir Sessions Podcast
		9084, // The Taxman
		8934, // The Great Composers
		6532, // The Beethoven 9
		7524, // -The Beethoven 9 Podcast
		7564, // -Colorado Matters Podcast
		9410, // -Who's Gonna Govern
	];

	/**
	 * Callback used to modify the mapping built being loaded.
	 *
	 * @param array $mapping Mapping.
	 * @return array
	 */
	public function mapping_filter( array $mapping ) : array {
		return $this->unique_taxonomy_ids_for_podcasts;
	}
}
