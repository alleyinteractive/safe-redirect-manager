<?php
/**
 * Colorado Wonders Widget
 *
 * @package CPR
 */

namespace CPR;

if ( ! class_exists( '\FM_Widget' ) ) {
	return;
}

/**
 * Class for Colorado Wonders widget.
 */
class Colorado_Wonders_Widget extends \FM_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'colorado_wonders_widget',
			__( 'Colorado Wonders Widget', 'cpr' ),
			[
				'description' => __( 'Displays a Colorado Wonders form widget', 'cpr' ),
			]
		);
	}

	/**
	 * Create a component from widget instance.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 * @return null|\CPR\Components\Widgets\Script_Embed
	 */
	public function create_component( $args, $instance ) : ?\CPR\Components\Widgets\Script_Embed {
		return ( new \CPR\Components\Widgets\Script_Embed() )
			->set_config( 'script_url', 'https://modules.wearehearken.com/cpr/embed/1392.js' );
	}
}
