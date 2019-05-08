<?php
/**
 * Feed for migrating Categories.
 *
 * @package CPR
 */

namespace CPR\Migration\Category;

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
	protected $data_slug = 'topics';

	/**
	 * A unique slug for this feed.
	 *
	 * @var string
	 */
	protected $sync_slug = 'category';

	/**
	 * Define the feed item to sync.
	 *
	 * @var string
	 */
	protected $feed_item_class = '\CPR\Migration\Category\Feed_Item';
}
