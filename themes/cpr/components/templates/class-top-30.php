<?php
/**
 * Top 30 Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * Top 30 template.
 */
class Top_30 extends \WP_Components\Component {

	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'top-30-template';

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
			 * Body.
			 */
			( new \CPR\Components\Column_Area() )
				->set_theme( 'oneColumn' )
				->append_children(
					[
						( new \WP_Components\Gutenberg_Content() )->set_post( $this->post ),
					]
				),

			/**
			 * Top 30 albums.
			 */
			( new \CPR\Components\Audio\Top_30() )
				->set_post( $this->post ),
		];
	}
}
