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

	use \WP_Components\Content_List;
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
			'header_text' => '',
			'header_link' => '',
		];
	}

	/**
	 * Create a widget content list item.
	 *
	 * @param int|\WP_Post $post_id Post ID or post object.
	 * @return \CPR\Components\Content_Item
	 */
	public function get_content_list_item( $post_id ) {

		// Track content item ID as already used.
		\Alleypack\Unique_WP_Query_Manager::add_used_post_ids( $post_id );

		return ( new \CPR\Components\Widgets\Content_List_Item() )->set_post( $post_id );
	}
}
