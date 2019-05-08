<?php
/**
 * Feed for migrating Underwriters.
 *
 * @package CPR
 */

namespace CPR\Migration\Guest_Author;

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
	protected $data_slug = 'person';

	/**
	 * A unique slug for this feed.
	 *
	 * @var string
	 */
	protected $sync_slug = 'guest-author';

	/**
	 * Define the feed item to sync.
	 *
	 * @var string
	 */
	protected $feed_item_class = '\CPR\Migration\Guest_Author\Feed_Item';

	/**
	 * Helper to set the byline for a given post.
	 *
	 * @param int   $post_id.          Post id.
	 * @param array $unique_author_ids Array of unique (legacy) guest author
	 *                                 ids.
	 */
	public static function set_bylines( int $post_id, array $unique_author_ids ) {

		global $coauthors_plus;

		$coauthor_slugs = array_filter( array_map(
			function( $unique_author_id ) {

				// Get or create author.
				$source = \CPR\Migration\Migration::instance()->get_source_data_by_id( 'person', $unique_author_id );
				$author = \CPR\Migration\Guest_Author\Feed_Item::get_or_create_object_from_source( $source );

				// Grab the coauthor slug.
				if ( $author instanceof \WP_Post ) {
					$author_slug = get_post_meta( $author->ID, 'cap-user_login', true );
					if ( ! empty( $author_slug ) ) {
						return $author_slug;
					}
				} else {

					// Use a default user and flag the situation in meta.
					update_post_meta( $post_id, 'missing_author', true );
					return 'colorado-public-radio-staff';
				}
			},
			$unique_author_ids
		) );

		if ( ! empty( $coauthor_slugs ) ) {
			$coauthors_plus->add_coauthors( $post_id, $coauthor_slugs, false );
		}
	}
}
