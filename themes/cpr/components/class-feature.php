<?php
/**
 * Feature component.
 *
 * @package CPR
 */

namespace CPR\Components;

/**
 * Feature.
 */
class Feature extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'feature';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'theme_name' => 'three-column',
		];
	}
}
