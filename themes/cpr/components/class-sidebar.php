<?php
/**
 * Sidebar component.
 *
 * @package CPR
 */

namespace CPR\Component;

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
	public function default_config() {
		return [
			'position' => 'left',
			'has_ad'   => false,
		];
	}
}
