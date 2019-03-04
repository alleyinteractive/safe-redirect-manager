<?php
/**
 * Header component.
 *
 * @package CPR
 */

namespace CPR\Components\Header;

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
	public function default_children() : array {
		return [
			new \CPR\Components\Logo(),
			new Sections(),
			( new Menu() )->set_menu( 'header' ),
		];
	}

	/**
	 * Hook into query being set.
	 *
	 * @return self
	 */
	public function query_has_set() : self {

		// Override with alternate children on certain pages.
		switch ( true ) {
			case 'landing-page' === $this->query->get( 'dispatch' ):
				$type = $this->query->get( 'landing-page-type' );
				if ( 'homepage' !== $type ) {
					$this->children = [
						( new \CPR\Components\Logo() )->set_config( 'type', $type ),
						( new Menu() )->set_menu( $type ),
					];
					return $this;
				}
				break;
			case $this->query->is_tax( 'section' ):
				$term = $this->query->get_queried_object();
				$this->children = [
					( new \CPR\Components\Logo() )->set_config( 'type', $term->slug ?? '' ),
					( new Menu() )->set_menu( $term->slug ?? '' ),
				];
				return $this;
			case $this->query->is_post_type_archive( 'top-30' ):
				$this->children = [
					( new \CPR\Components\Logo() )->set_config( 'type', 'openair' ),
					( new Menu() )->set_menu( 'openair' ),
				];
				return $this;
		}

		return $this;
	}
}
