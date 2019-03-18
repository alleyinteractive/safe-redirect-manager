<?php
/**
 * Footer component.
 *
 * @package CPR
 */

namespace CPR\Components\Footer;

/**
 * Footer.
 */
class Footer extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'footer';

	/**
	 * Define default children.
	 *
	 * @return array Default children.
	 */
	public function default_children() : array {
		// @TODO determine if it's cool to hard-code the order of title links or if we need to make it more customizable.
		$menus = array_map(
			function( $menu, $title_link ) {
				return ( new \WP_Components\Menu() )
					->set_menu( $menu )
					->parse_wp_menu()
					->wp_menu_set_title()
					->set_theme( 'footer' )
					->set_config( 'title_link', $title_link )
					->set_child_themes( [ 'menu-item' => 'footer' ] );
			},
			[
				'footer-1',
				'footer-2',
				'footer-3',
				'footer-4',
			],
			[
				home_url(),
				home_url( '/news/' ),
				home_url( '/classical/' ),
				home_url( '/openair/' ),
			]
		);

		return array_merge(
			[
				( new \CPR\Components\Logo() )
					->set_theme( 'footer' ),
				( new \CPR\Components\Modules\Newsletter() )
					->set_theme( 'footer' )
					->merge_config(
						[
							'background_color' => 'transparent',
							'tagline'          => '',
						]
					),
				( new \WP_Components\HTML() )
					->set_config(
						'content',
						sprintf(
							'© %1$s %2$s <a href="%3$s">%4$s</a>.',
							date( 'Y' ),
							__( 'Colorado Public Radio. All Rights Reserved.', 'cpr' ),
							home_url( '/privacy-policy/' ),
							__( 'Privacy Policy', 'cpr' )
						)
					),
				( new \CPR\Components\Social_Links() )
					->parse_from_fm_data(),
			],
			$menus
		);
	}
}
