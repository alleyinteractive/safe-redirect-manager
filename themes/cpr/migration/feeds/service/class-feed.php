<?php
/**
 * Feed for migrating Services.
 *
 * @package CPR
 */

namespace CPR\Migration\Service;

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
	protected $data_slug = 'service';

	/**
	 * A unique slug for this feed.
	 *
	 * @var string
	 */
	protected $sync_slug = 'service';

	/**
	 * Define the feed item to sync.
	 *
	 * @var string
	 */
	protected $feed_item_class = '\CPR\Migration\Service\Feed_Item';

	/**
	 * Helper to set the section for a given post.
	 *
	 * @param int        $post_id.          Post id.
	 * @param string|int $section_unique_id Unique (legacy) id for this
	 *                                      service/section.
	 */
	public static function set_section( $post_id, $section_unique_id ) {

		// Attempt to retreieve the section term.
		$section_term = \CPR\Migration\Service\Feed_Item::get_object_by_unique_id( $section_unique_id );

		// Validate and set.
		if ( $section_term instanceof \WP_Term ) {
			wp_set_object_terms(
				$post_id,
				$section_term->term_id,
				'section'
			);
		}
	}
}
