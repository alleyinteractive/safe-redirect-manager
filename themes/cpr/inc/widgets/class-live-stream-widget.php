<?php
/**
 * Live Stream Widget
 *
 * @package CPR
 */

namespace CPR;

if ( ! class_exists( '\FM_Widget' ) ) {
	return;
}

/**
 * Class for Live Stream widget.
 */
class Live_Stream_Widget extends \FM_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'live_stream_widget',
			__( 'Live Stream Widget', 'cpr' ),
			[
				'description' => __( 'Displays a live stream component.', 'cpr' ),
			]
		);
	}

	/**
	 * Create a component from widget instance.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 * @return null|\CPR\Components\Audio\Live_Stream
	 */
	public function create_component( $args, $instance ) : ?\CPR\Components\Audio\Live_Stream {
		if ( empty( $instance['stream_source'] ) ) {
			return null;
		}

		return ( new \CPR\Components\Audio\Live_Stream() )
			->set_source( $instance['stream_source'] )
			->set_config( 'count', 1 )
			->set_theme( 'sidebar' );
	}

	/**
	 * Define the fields that should appear in the widget.
	 *
	 * @return array Fieldmanager fields.
	 */
	protected function fieldmanager_children() {
		return [
			'stream_source' => new \Fieldmanager_Select(
				[
					'label'   => __( 'Stream', 'cpr' ),
					'options' => [
						'classical' => __( 'Classical', 'cpr' ),
						'indie'     => __( 'Indie', 'cpr' ),
						'news'      => __( 'News', 'cpr' ),
					],
				]
			),
		];
	}
}
