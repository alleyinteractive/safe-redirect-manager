<?php
/**
 * Primary Navigation component.
 *
 * @package CPR
 */

namespace CPR\Components\Primary_Navigation;

/**
 * Primary Navigation.
 */
class Primary_Navigation extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'primary-navigation';

	/**
	 * Define default children.
	 *
	 * @return array Default children.
	 */
	public function default_children() : array {
		return [
			( new Menu() )->set_menu( 'primary-navigation' ),
		];
	}
}
