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

	use \WP_Components\WP_Post;
	use \WP_Components\WP_Term;
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
			( new \CPR\Components\Logo() )
				->set_theme( 'primary' ),
			( new \WP_Components\Menu() )
				->set_menu( 'main' )
				->parse_wp_menu()
				->set_theme( 'header' )
				->set_child_themes( [ 'menu-item' => 'header' ] ),
		];
	}

	/**
	 * Set the component to use the main logo and nav menu.
	 */
	public function set_cpr_header() : self {
		return $this->set_header( 'main' );
	}

	/**
	 * Set the component to use the news logo and nav menu.
	 */
	public function set_news_header() : self {
		return $this->set_header( 'news' );
	}

	/**
	 * Set the component to use the classical logo and nav menu.
	 */
	public function set_classical_header() : self {
		return $this->set_header( 'classical' );
	}

	/**
	 * Set the component to use the indie logo and nav menu.
	 */
	public function set_indie_header() : self {
		return $this->set_header( 'indie' );
	}

	/**
	 * Set the component to use the logo and nav menu.
	 *
	 * @param string $type Type to set.
	 */
	public function set_header( string $type ) : self {
		$this->set_children(
			[
				/**
				 * Logo.
				 */
				( new \CPR\Components\Logo() )
					->set_config( 'type', $type )
					->set_theme( 'primary' ),

				/**
				 * Menu.
				 */
				( new \WP_Components\Menu() )
					->set_menu( $type )
					->parse_wp_menu()
					->set_theme( 'header' )
					->set_child_themes( [ 'menu-item' => 'header' ] ),
			]
		);
		return $this;
	}

	/**
	 * Hook into post being set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		$sections = wp_get_post_terms( $this->get_post_id(), 'section' );
		if ( $sections[0] instanceof \WP_Term ) {
			$this->set_term( $sections[0] );
		}
		return $this;
	}

	/**
	 * Hook into term being set.
	 *
	 * @return self
	 */
	public function term_has_set() : self {
		if ( ! in_array( $this->term->slug, [ 'news', 'classical', 'indie' ], true ) ) {
			$this->set_cpr_header();
		} else {
			$this->set_header( $this->term->slug );
		}
		return $this;
	}
}
