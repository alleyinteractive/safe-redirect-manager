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

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'widget-content-list';

	/**
	 * Available themes for this component. If you attempt to set a theme that is
	 * not in this array, it will fail (and fall back to 'default').
	 *
	 * @var array.
	 */
	public $themes = [
		'default', // Used for a standard river of content.
	];

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
		return ( new \CPR\Components\Widgets\Content_List_Item() )->set_post( $post_id );
	}
}
