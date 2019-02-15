<?php
/**
 * Article Template Component.
 *
 * @package CPR
 */

namespace CPR\Component\Templates;

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
	 */
	public function post_has_set() {
		$body = new \WP_Components\Body();
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
			// ( new \CPR\Component\Content\Header() )
			// 	->set_post( $this->post ),

			/**
			 * Content Body.
			 */
			// ( new \CPR\Component\Content\Body() )
			// 	->set_post( $this->post ),

			/**
			 * Content Footer.
			 */
			( new \CPR\Component\Content\Footer() )
				->set_post( $this->post ),

			/**
			 * Recirculation module.
			 */
			( new \CPR\Component\Modules\Content_List() )
				->set_config( 'theme', 'grid' )
				->set_config( 'image_size', 'grid_item' )
				->set_config( 'heading', __( 'Related Content', 'cpr' ) )
				->parse_from_jetpack_related( $this->get_post_id(), 3, [] ),
		];
	}
}
