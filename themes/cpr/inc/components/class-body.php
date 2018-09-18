<?php
/**
 * Class file for the Body component.
 *
 * @package Cpr
 */

namespace Cpr\Component;

/**
 * Defines the Body component.
 */
class Body extends \WP_Irving\Component\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'body';

	/**
	 * Define the default config of a Body component.
	 *
	 * @return array Default config values for this component.
	 */
	public function default_config() {
		return [
			'classes' => [],
		];
	}
}
