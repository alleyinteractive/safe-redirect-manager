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
			 * Article Header
			 */

			/**
			 * Article Body
			 */

			/**
			 * Disqus Comments.
			 */

			/**
			 * Recirculation module.
			 */
			( new \CPR\Component\Modules\Content_List() )
				->set_config( 'theme', 'grid' )
				->set_config( 'heading', __( 'Related Content', 'cpr' ) )
				->parse_from_jetpack_related( $this->get_post_id(), 3, [] ),
		];
	}
}
