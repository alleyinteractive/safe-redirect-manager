<?php
/**
 * Feed for migrating Images.
 *
 * @package CPR
 */

namespace CPR\Migration\Image;

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
	protected $data_slug = 'image';

	/**
	 * A unique slug for this feed.
	 *
	 * @var string
	 */
	protected $sync_slug = 'image';

	/**
	 * Define the feed item to sync.
	 *
	 * @var string
	 */
	protected $feed_item_class = '\CPR\Migration\Image\Feed_Item';

	/**
	 * Helper to set the section for a given post.
	 *
	 * @param int        $post_id         Post id.
	 * @param string|int $unique_image_id Unique (legacy) id for this
	 *                                    service/section.
	 */
	public static function set_featured_image( int $post_id, $unique_image_id ) {

		// Attempt to retreieve the section term.
		$source     = \CPR\Migration\Migration::instance()->get_source_data_by_id( 'image', $unique_image_id );
		$attachment = \CPR\Migration\Image\Feed_Item::get_or_create_object_from_source( $source );

		// Validate attachment
		if ( $attachment instanceof \WP_Post ) {
			update_post_meta( $post_id, '_thumbnail_id', $attachment->ID );
		}
	}
}
