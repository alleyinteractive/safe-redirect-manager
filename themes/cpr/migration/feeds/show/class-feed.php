<?php
/**
 * Feed for migrating Shows.
 *
 * @package CPR
 */

namespace CPR\Migration\Show;

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
	protected $sync_slug = 'show';

	/**
	 * Define the feed item to sync.
	 *
	 * @var string
	 */
	protected $feed_item_class = '\CPR\Migration\Show\Feed_Item';

	/**
	 * The unique ids for the show taxonomy objects.
	 *
	 * @var array
	 */
	public $unique_taxonomy_ids_for_shows = [
		70,   // Colorado Matters
		6345, // OpenAir Live & Local
	];

	/**
	 * Callback used to modify the mapping built being loaded.
	 *
	 * @param array $mapping Mapping.
	 * @return array
	 */
	public function mapping_filter( array $mapping ) : array {
		return $this->unique_taxonomy_ids_for_shows;
	}
}
