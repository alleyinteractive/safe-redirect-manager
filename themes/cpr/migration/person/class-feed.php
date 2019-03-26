<?php
/**
 * Class for parsing a person feed.
 *
 * @package CPR
 */

namespace CPR\Migration\Person;

/**
 * Feed.
 */
class Feed extends \Alleypack\Sync_Script\Feed {

	/**
	 * A unique slug for this feed.
	 *
	 * @var string
	 */
	protected $sync_slug = 'person';

	/**
	 * Define the feed item to sync.
	 *
	 * @var string
	 */
	protected $feed_item_class = '\CPR\Migration\Person\Feed_Item';

	/**
	 * Batch size.
	 *
	 * @var int
	 */
	protected $batch_size = 1;

	/**
	 * Load feed items.
	 *
	 * @param int $limit  Feed limit.
	 * @param int $offset Feed offset.
	 * @return array
	 */
	public function load_source_feed_data( int $limit, int $offset ) : array {
		$files = \CPR\Migration\Migration::get_directory_json( $this->sync_slug );

		if ( isset( $files[ $offset ] ) ) {
			$source_object_id = str_replace( '.json', '', $files[ $offset ] );
			$source_object = \CPR\Migration\Migration::load_json_data( $source_object_id, $this->sync_slug );

			return [ $source_object ];
		}

		return [];
	}
}
