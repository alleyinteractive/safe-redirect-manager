<?php
/**
 * Audio Element component.
 *
 * @package CPR
 */

namespace CPR\Components\Audio;

/**
 * Audio Element.
 */
class Element extends \WP_Components\Component {

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
