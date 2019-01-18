<?php
/**
 * Slim Navigation component.
 *
 * @package CPR
 */

namespace CPR\Component\Slim_Navigation;

/**
 * Slim Navigation.
 */
class Slim_Navigation extends \WP_Component\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'slim-navigation';

	/**
	 * Define default children.
	 *
	 * @return array Default children.
	 */
	public function default_children() {
		return [
			new Menu(),
			new Listen_Live(),
			new \CPR\Component\Social_Links(),
			new Search(),
			new \CPR\Component\Donate_Button(),
		];
	}
}
