<?php
/**
 * Generic CPR feed for the migration.
 *
 * @package CPR
 */

namespace CPR\Migration;

use function Alleypack\Sync_Script\alleypack_log;

/**
 * CPR Feed for using a post type as a data source.
 */
class Post_Datasource_Feed extends Feed {

	/**
	 * Legacy slug for this feed.
	 *
	 * @var string
	 */
	protected $data_slug = 'story';

	/**
	 * Post type for this feed to execute on itself.
	 *
	 * @var string
	 */
	public $post_type = 'post';

	/**
	 * Load source data using a limit and offset.
	 *
	 * @param int $limit  Feed limit.
	 * @param int $offset Feed offset.
	 * @return bool Loaded successfully?
	 */
	public function load_source_data_by_limit_and_offset( int $limit, int $offset ) : bool {

		// Run a post query.
		$source_query = new \WP_Query(
			[
				'offset'         => $offset,
				'post_type'      => $this->post_type,
				'posts_per_page' => $limit,
			]
		);

		$data = array_map(
			function( $post ) {
				// Get the unique ID so we can get the source data.
				$unique_id = get_post_meta( $post->ID, $this->feed_item_class::get_unique_id_key(), true );
				$data      = Migration::instance()->get_source_data_by_id( $this->data_slug, absint( $unique_id ) );
				return (array) $data;
			},
			$source_query->posts ?? []
		);

		$this->set_source_data( $data );

		return $this->has_source_data();
	}
}
