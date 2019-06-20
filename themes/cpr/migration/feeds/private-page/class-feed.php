<?php
/**
 * Feed for migrating Private Pages.
 *
 * @package CPR
 */

namespace CPR\Migration\Private_Page;

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
	protected $data_slug = 'private_story';

	/**
	 * A unique slug for this feed.
	 *
	 * @var string
	 */
	protected $sync_slug = 'private-page';

	/**
	 * Define the feed item to sync.
	 *
	 * @var string
	 */
	protected $feed_item_class = '\CPR\Migration\Private_Page\Feed_Item';
}
