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

	use \WP_Components\WP_Query;

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
		];
	}

	/**
	 * Hook into query being set.
	 */
	public function query_has_set() {
		// Also need a different logo and no sections child, soooo maybe instead of appending a child, just completely override the children array? I like that better I think.

		// Default to using the header menu.
		$menu = 'header';

		// Override with an alternate menu on certain pages.
		switch ( true ) {
			case 'landing-page' === $this->query->get( 'dispatch' ):
				$type = $this->query->get( 'landing-page-type' );
				if ( 'homepage' !== $type ) {
					$menu = $this->query->get( 'landing-page-type' );
				}
				break;
		}

		// Append the menu child.
		$this->append_child( ( new Menu() )->set_menu( $menu ) );

		return $this;
	}
}
