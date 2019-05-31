<?php
/**
 * Calendar component.
 *
 * @package CPR
 */

namespace CPR\Components\Widgets;

/**
 * Calendar.
 */
class Content_List extends \WP_Components\Component {

	use \CPR\Backfill;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'widget-content-list';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'heading'      => '',
			'heading_link' => '',
		];
	}

	/**
	 * Parse a WP_Query object to be used by this component.
	 *
	 * @param \WP_Query $wp_query      \WP_Query object.
	 * @param integer   $backfill_to   How many content items should this component
	 *                                 have.
	 * @param array     $backfill_args WP_Query arguments for the backfill.
	 * @return Content_List
	 */
	public function parse_from_wp_query( \WP_Query $wp_query, $backfill_to = 0, $backfill_args = [] ) : Content_List {

		// Extract post IDs from the query.
		$content_item_ids = wp_list_pluck( $wp_query->posts ?? [], 'ID' );

		// Backfill as needed.
		$content_item_ids = $this->backfill_content_item_ids(
			$content_item_ids,
			$backfill_to,
			$backfill_args
		);

		foreach ( $content_item_ids as $content_item_id ) {
			$this->children[] = $this->create_content_item( $content_item_id );
		}

		return $this;
	}

	/**
	 * Parse the stored FM data to be used by this component.
	 *
	 * @param array   $fm_data       Stored Fieldmanager data.
	 * @param integer $backfill_to   How many content items should this component
	 *                               have.
	 * @param array   $backfill_args WP_Query arguments for the backfill.
	 * @return Content_List
	 */
	public function parse_from_fm_data( array $fm_data, $backfill_to = 0, $backfill_args = [] ) : Content_List {

		$content_item_ids = $this->backfill_content_item_ids(
			(array) ( $fm_data['content_item_ids'] ?? [] ),
			$backfill_to,
			$backfill_args
		);

		$content_item_ids = [ 8344 ];

		foreach ( $content_item_ids as $content_item_id ) {
			$this->children[] = $this->create_content_item( $content_item_id );
		}

		return $this;
	}

	/**
	 * Create a content item.
	 *
	 * @param number $content_item_id Post ID for content item.
	 * @return \CPR\Components\Content_Item
	 */
	public function create_content_item( $content_item_id ) {

		// Track content item ID as already used.
		\Alleypack\Unique_WP_Query_Manager::add_used_post_ids( $content_item_id );

		return ( new \CPR\Components\Widgets\Content_List_Item() )
			->set_theme( $post->post_type )
			->set_post( $content_item_id );
	}

	/**
	 * Component Fieldmanager fields.
	 *
	 * @return array Fieldmanager fields.
	 */
	public function get_fm_fields() : array {
		return [
			'heading'          => new \Fieldmanager_TextField(
				[
					'label'         => __( 'Heading', 'cpr' ),
					'default_value' => $this->get_config( 'heading' ),
				]
			),
			'content_item_ids' => new \Fieldmanager_Zone_Field(
				[
					'label'      => __( 'Events', 'cpr' ),
					'post_limit' => 4,
					'query_args' => [
						'post_type' => [ 'event' ],
					],
				]
			),
		];
	}
}
