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
		return [
			new \CPR\Components\Logo(),
			new \CPR\Components\Modules\Newsletter(),
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
			( new Menu() )->set_menu( 'footer-1' ),
			( new Menu() )->set_menu( 'footer-2' ),
			( new Menu() )->set_menu( 'footer-3' ),
			( new Menu() )->set_menu( 'footer-4' ),
		];
	}
}
