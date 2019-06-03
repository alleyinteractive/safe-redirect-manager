<?php
/**
 * External Link Widget
 *
 * @package CPR
 */

namespace CPR;

if ( ! class_exists( '\FM_Widget' ) ) {
	return;
}

/**
 * Class for External Link widget.
 */
class External_Link_Widget extends \FM_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'external_link_widget',
			__( 'External Link Widget', 'cpr' ),
			[
				'description' => __( 'Display an external link', 'cpr' ),
			]
		);
	}

	/**
	 * Create a component from widget instance.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 * @return null|\CPR\Components\Widgets\Content_List_Item
	 */
	public function create_component( $args, $instance ) : ?\CPR\Components\Widgets\Content_List_Item {
		if ( empty( $instance['id'][0] ) ) {
			return null;
		}

		return ( new \CPR\Components\Widgets\Content_List_Item() )
			->set_post( $instance['id'][0] );
	}

	/**
	 * Define the fields that should appear in the widget.
	 *
	 * @return array Fieldmanager fields.
	 */
	protected function fieldmanager_children() {
		return [
			'id' => new \Fieldmanager_Zone_Field(
				[
					'label'      => __( 'Post', 'cpr' ),
					'post_limit' => 1,
					'query_args' => [
						'post_type' => [ 'external-link' ],
					],
				]
			),
		];
	}
}
