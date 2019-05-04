<?php
/**
 * Feed for migrating Underwriters.
 *
 * @package CPR
 */

namespace CPR\Migration\User;

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
	protected $data_slug = 'users';

	/**
	 * A unique slug for this feed.
	 *
	 * @var string
	 */
	protected $sync_slug = 'user';

	/**
	 * Define the feed item to sync.
	 *
	 * @var string
	 */
	protected $feed_item_class = '\CPR\Migration\User\Feed_Item';
}
