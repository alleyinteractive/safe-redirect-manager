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

	/**
	 * Helper to set the categories for a given post.
	 *
	 * @param int        $post_id             Post id.
	 * @param string|int $unique_category_ids Unique (legacy) topic ids.
	 */
	public static function set_categories( int $post_id, $unique_category_ids ) {

		$post_category_ids = array_filter(
			array_map(
				function( $unique_category_id ) {

					// Attempt to get the post_category term.
					$source             = \CPR\Migration\Migration::instance()->get_source_data_by_id( 'topics', $unique_category_id );
					$post_category_term = \CPR\Migration\Post_Tag\Feed_Item::get_or_create_object_from_source( $source );

					if ( $post_category_term instanceof \WP_Term ) {
						return absint( $post_category_term->term_id );
					}
				},
				$unique_category_ids
			)
		);

		// Validate and set.
		if ( ! empty( $post_category_ids ) ) {
			wp_set_object_terms(
				$post_id,
				$post_category_ids,
				'category'
			);
		}
	}
}
