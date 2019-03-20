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
				->append_children(
					[
						( new \WP_Components\Menu_Item() )
							->merge_config(
								[
									'id'    => 0,
									'label' => __( 'News', 'cpr' ),
									'url'   => home_url( '/news/' ),
								]
							),
						( new \WP_Components\Menu_Item() )
							->merge_config(
								[
									'id'    => 1,
									'label' => __( 'Classical', 'cpr' ),
									'url'   => home_url( '/classical/' ),
								]
							),
						( new \WP_Components\Menu_Item() )
							->merge_config(
								[
									'id'    => 2,
									'label' => __( 'OpenAir', 'cpr' ),
									'url'   => home_url( '/openair/' ),
								]
							),
					]
				)
				->set_theme( 'sections' )
				->set_child_themes( [ 'menu-item' => 'sections' ] ),
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
						( new \WP_Components\Menu() )->set_menu( $type ),
					];
					return $this;
				}
				break;
			case $this->query->is_tax( 'section' ):
				$term = $this->query->get_queried_object();
				$this->children = [
					( new \CPR\Components\Logo() )
						->set_config( 'type', $term->slug ?? '' )
						->set_theme( 'primary' ),
					( new \WP_Components\Menu() )->set_menu( $term->slug ?? '' ),
				];
				return $this;
			case $this->query->is_post_type_archive( 'top-30' ):
				$this->children = [
					( new \CPR\Components\Logo() )
						->set_config( 'type', 'openair' )
						->set_theme( 'primary' ),
					( new \WP_Components\Menu() )->set_menu( 'openair' ),
				];
				return $this;
		}

		return $this;
	}
}
