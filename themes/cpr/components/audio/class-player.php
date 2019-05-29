<?php
/**
 * Player component.
 *
 * @package CPR
 */

namespace CPR\Components\Audio;

/**
 * Player.
 */
class Player extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'audio-player';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'src' => '',
		];
	}
}
