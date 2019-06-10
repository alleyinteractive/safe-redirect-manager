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
			( new \CPR\Components\Logo() )
				->set_theme( 'primary' ),
			( new \WP_Components\Menu() )
				->set_menu( 'header' )
				->parse_wp_menu()
				->set_theme( 'header' )
				->set_child_themes( [ 'menu-item' => 'header' ] ),
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
						( new \CPR\Components\Logo() )
							->set_config( 'type', $type )
							->set_theme( 'primary' ),
						( new \WP_Components\Menu() )
							->set_menu( $type )
							->parse_wp_menu()
							->set_theme( 'header' )
							->set_child_themes( [ 'menu-item' => 'header' ] ),
					];
					return $this;
				}
				break;

			case $this->query->is_post_type_archive( 'top-30' ):
			case $this->query->is_singular( 'top-30' ):
				$this->children = [
					( new \CPR\Components\Logo() )
						->set_config( 'type', 'indie' )
						->set_theme( 'primary' ),
					( new \WP_Components\Menu() )
						->set_menu( 'indie' )
						->parse_wp_menu()
						->set_theme( 'header' )
						->set_child_themes( [ 'menu-item' => 'header' ] ),
				];
				return $this;

			case $this->query->is_single():
				$sections = wp_get_post_terms( ( $this->query->post->ID ?? 0 ), 'section' );
				if ( ! empty( $sections[0] ) && $sections[0] instanceof \WP_Term ) {
					$this->children = [
						( new \CPR\Components\Logo() )
							->set_config( 'type', $sections[0]->slug ?? '' )
							->set_theme( 'primary' ),
						( new \WP_Components\Menu() )
							->set_menu( $sections[0]->slug ?? '' )
							->parse_wp_menu()
							->set_theme( 'header' )
							->set_child_themes( [ 'menu-item' => 'header' ] ),
					];
				}
				return $this;

			case $this->query->is_tax( 'section' ):
				$term = $this->query->get_queried_object();
				$this->children = [
					( new \CPR\Components\Logo() )
						->set_config( 'type', $term->slug ?? '' )
						->set_theme( 'primary' ),
					( new \WP_Components\Menu() )
						->set_menu( $term->slug ?? '' )
						->parse_wp_menu()
						->set_theme( 'header' )
						->set_child_themes( [ 'menu-item' => 'header' ] ),
				];
				return $this;
		}

		return $this;
	}
}
