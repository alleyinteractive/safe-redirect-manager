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
			'src'   => '',
			'title' => [],
		];
	}
}
