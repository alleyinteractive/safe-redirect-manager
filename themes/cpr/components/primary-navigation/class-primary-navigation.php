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
			( new \CPR\Components\Search\Search_Bar() )
				->set_theme( 'primaryNav' ),
			( new Menu() )
				->set_menu( 'primary-navigation' )
				->children_callback( function ( $menu_item ) {
					// Set submenus to use regular 'menu' name.
					$menu_item->children_callback( function( $submenu ) {
						// Set submenu items to use regular 'menu-item' name.
						$submenu->set_name( 'menu' )
							->children_callback( function( $submenu_item ) {
								$submenu_item->set_name( 'menu-item' );

								return $submenu_item;
							} );

						return $submenu;
					} );

					return $menu_item;
				} )
				->set_child_themes(
					[
						'menu'      => 'primaryNavSubmenu',
						'menu-item' => 'primaryNavSubmenu',
					]
				),
		];
	}
}
