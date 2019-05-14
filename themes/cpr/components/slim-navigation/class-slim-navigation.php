<?php
/**
 * Slim Navigation component.
 *
 * @package CPR
 */

namespace CPR\Components\Slim_Navigation;

/**
 * Slim Navigation.
 */
class Slim_Navigation extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'slim-navigation';

	/**
	 * Define default children.
	 *
	 * @return array Default children.
	 */
	public function default_children() : array {
		return [
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
				->set_theme( 'slimNav' )
				->set_child_themes( [ 'menu-item' => 'slimNav' ] ),
			new Search(),
			new \CPR\Components\Donate\Donate_Button(),
		];
	}
}
