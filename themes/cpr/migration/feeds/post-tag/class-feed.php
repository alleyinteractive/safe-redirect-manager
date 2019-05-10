<?php
/**
 * Feed for migrating Tags.
 *
 * @package CPR
 */

namespace CPR\Migration\Post_Tag;

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
	protected $sync_slug = 'post-tag';

	/**
	 * Define the feed item to sync.
	 *
	 * @var string
	 */
	protected $feed_item_class = '\CPR\Migration\Post_Tag\Feed_Item';

	/**
	 * Helper to set the tags for a given post.
	 *
	 * @param int        $post_id       Post id.
	 * @param string|int $unique_tag_ids Unique (legacy) taxonomy ids.
	 */
	public static function set_tags( int $post_id, $unique_tag_ids ) {

		$post_tag_ids = array_filter(
			array_map(
				function( $unique_tag_id ) {

					// Attempt to get the post_tag term.
					$source        = \CPR\Migration\Migration::instance()->get_source_data_by_id( 'taxonomy', $unique_tag_id );
					$post_tag_term = \CPR\Migration\Post_Tag\Feed_Item::get_or_create_object_from_source( $source );

					if ( $post_tag_term instanceof \WP_Term ) {
						return absint( $post_tag_term->term_id );
					}
				},
				$unique_tag_ids
			)
		);

		// Validate and set.
		if ( ! empty( $post_tag_ids ) ) {
			wp_set_object_terms(
				$post_id,
				$post_tag_ids,
				'post_tag'
			);
		}
	}
}
