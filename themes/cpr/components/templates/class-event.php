<?php
/**
 * Event Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * Event template.
 */
class Event extends \WP_Components\Component {

	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'event-template';

	/**
	 * Hook into post being set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		$body           = new \WP_Components\Body();
		$body->children = array_filter( $this->get_components() );
		$this->append_child( $body );
		return $this;
	}

	/**
	 * Get an array of all components.
	 *
	 * @return array
	 */
	public function get_components() : array {
		return [
			/**
			 * Event Body.
			 */
			( new \CPR\Components\Events\Body() )
				->set_post( $this->post ),

			/**
			 * Recirculation module.
			 */
			( new \CPR\Components\Column_Area() )
				->set_theme( 'oneColumn' )
				->set_config( 'heading', __( 'Latest Stories', 'cpr' ) )
				->append_children(
					[
						( new \CPR\Components\Modules\Content_List() )
							->set_config( 'image_size', 'grid_item' )
							->parse_from_wp_query(
								new \WP_Query(
									[
										'post_type'      => [ 'post' ],
										'posts_per_page' => 4,
									]
								)
							)
							->set_theme( 'gridLarge' )
							->set_child_themes(
								[
									'content-item'  => 'grid',
									'eyebrow'       => 'small',
									'content-title' => 'grid',
								]
							),
					]
				),
		];
	}
}
