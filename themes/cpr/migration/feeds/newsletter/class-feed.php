<?php
/**
 * Feed for migrating Newsletters.
 *
 * @package CPR
 */

namespace CPR\Migration\Newsletter;

/**
 * Feed.
 */
class Feed extends \CPR\Migration\Post_Datasource_Feed {

	// Enable functionality for this feed.
	use \Alleypack\Sync_Script\Endpoint;
	use \Alleypack\Sync_Script\GUI;

	/**
	 * Post type for this feed to execute on itself.
	 *
	 * @var string
	 */
	public $post_type = 'newsletter-single';

	/**
	 * Legacy slug for this feed.
	 *
	 * @var string
	 */
	protected $data_slug = 'story';

	/**
	 * A unique slug for this feed.
	 *
	 * @var string
	 */
	protected $sync_slug = 'newsletter';

	/**
	 * Define the feed item to sync.
	 *
	 * @var string
	 */
	protected $feed_item_class = '\CPR\Migration\Newsletter\Feed_Item';
}
