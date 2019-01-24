<?php
/**
 * Header component.
 *
 * @package CPR
 */

namespace CPR\Component\Header;

/**
 * Header.
 */
class Header extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'header';

	/**
	 * Define default children.
	 *
	 * @return array Default children.
	 */
	public function default_children() {
		return [
			new \CPR\Component\Logo(),
			new Sections(),
			new Menu(),
		];
	}
}
