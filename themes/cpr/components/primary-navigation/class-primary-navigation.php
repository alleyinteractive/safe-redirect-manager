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
			( new \WP_Components\Menu() )
				->set_menu( 'primary-navigation' )
				->parse_wp_menu()
				->set_theme( 'primary-nav' )
				->set_child_themes( [ 'menu-item' => 'primary-nav' ] ),
		];
	}
}
