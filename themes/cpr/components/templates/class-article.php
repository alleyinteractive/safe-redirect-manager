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
			 * Content Header.
			 */
			( new \CPR\Components\Content\Header() )
				->set_post( $this->post ),

			/**
			 * Content Body.
			 */
			( new \CPR\Components\Content\Body() )
				->set_post( $this->post ),

			/**
			 * Content Footer.
			 */
			( new \CPR\Components\Content\Footer() )
				->set_post( $this->post ),

			/**
			 * Recirculation module.
			 */
			( new \CPR\Components\Column_Area() )
				->set_theme( 'oneColumn' )
				->set_config( 'heading', __( 'Related Content', 'cpr' ) )
				->append_children(
					[
						( new \CPR\Components\Modules\Content_List() )
							->set_config( 'image_size', 'grid_item' )
							->parse_from_jetpack_related( $this->get_post_id(), 4, [] )
							->set_theme( 'gridLarge' )
							->set_child_themes(
								[
									'content-item' => 'grid',
									'eyebrow'      => 'small',
									'title'        => 'grid',
								]
							),
					]
				),
		];
	}
}
