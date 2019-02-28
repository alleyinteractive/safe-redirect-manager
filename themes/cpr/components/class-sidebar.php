<?php
/**
 * Sidebar component.
 *
 * @package CPR
 */

namespace CPR\Components;

/**
 * Sidebar.
 */
class Sidebar extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'sidebar';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'position' => 'left',
			'has_ad'   => false,
		];
	}
}
