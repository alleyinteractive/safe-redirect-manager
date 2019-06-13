<?php
/**
 * Page Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * Page template.
 */
class Page extends \WP_Components\Component {

	use \WP_Components\WP_Post;
	use \CPR\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'page-template';

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
			 */
			( new \CPR\Components\Content\Body() )
				->set_post( $this->post ),
		];
	}
}
