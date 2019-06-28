<?php
/**
 * Article Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * Article template.
 */
class Article extends \WP_Components\Component {

	use \WP_Components\WP_Post;
	use \CPR\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'article-template';

	/**
	 * Hook into post being set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		$body = ( new \WP_Components\Body() )
			->set_config( 'body_classes', $this->get_section_slug() );
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
			 * Content Header.
			 */
			( new \CPR\Components\Content\Header() )
				->set_post( $this->post ),

			/**
			 * Content Body.
			 *
			 * @todo Extract the grid into this component to more easily build
			 * the layout.
			 * @todo Move components that were formerly in \Content\Footer into Body.
			 */
			( new \CPR\Components\Content\Body() )
				->set_post( $this->post ),

			/**
			 * Recirculation module.
			 */
			( new \CPR\Components\Column_Area() )
				->set_theme( 'oneColumn' )
				->set_config( 'heading', __( 'Recent Content', 'cpr' ) )
				->append_children(
					[
						( new \CPR\Components\Modules\Content_List() )
							->set_config( 'image_size', 'grid_item' )
							->parse_from_ids( [], 4, [] )
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
