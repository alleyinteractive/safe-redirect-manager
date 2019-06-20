<?php
/**
 * Feed for migrating Audio.
 *
 * @package CPR
 */

namespace CPR\Migration\Audio;

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
	protected $data_slug = 'audio';

	/**
	 * A unique slug for this feed.
	 *
	 * @var string
	 */
	protected $sync_slug = 'audio';

	/**
	 * Define the feed item to sync.
	 *
	 * @var string
	 */
	protected $feed_item_class = '\CPR\Migration\Audio\Feed_Item';

	/**
	 * Helper to set the audio files for a given post.
	 *
	 * @param int        $post_id         Post id.
	 * @param string|int $unique_audio_id Unique (legacy) id for this
	 *                                    service/section.
	 */
	public static function set_audio( int $post_id, $unique_audio_id ) {

		// Attempt to retrieve the image.
		$source     = \CPR\Migration\Migration::instance()->get_source_data_by_id( 'image', $unique_audio_id );
		$attachment = \CPR\Migration\Audio\Feed_Item::get_or_create_object_from_source( $source );

		// Validate attachment.
		if ( $attachment instanceof \WP_Post ) {
			update_post_meta( $post_id, '_thumbnail_id', $attachment->ID );
		}
	}
}
