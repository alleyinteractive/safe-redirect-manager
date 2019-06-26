<?php
/**
 * Play/Pause Button component.
 *
 * @package CPR
 */

namespace CPR\Components\Audio;

/**
 * Play/Pause Button.
 */
class Play_Pause_Button extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'play-pause-button';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'src'                  => '',
			'title'                => [],
			'live_stream_endpoint' => '',
			'live_stream_source'   => '',
		];
	}

	/**
	 * When the title config is set, ensure the vaalues are html url decoded.
	 */
	public function title_config_has_set() {
		if ( is_array( $this->get_config( 'title' ) ) ) {
			$this->set_config( 'title', array_map( 'html_entity_decode', $this->get_config( 'title' ) ), false );
		}
	}
}
