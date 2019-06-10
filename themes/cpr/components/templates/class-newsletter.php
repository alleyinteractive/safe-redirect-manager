<?php
/**
 * Newsletter Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * Newsletter template.
 */
class Newsletter extends \WP_Components\Component {

	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'newsletter-template';

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
			 * Newsletter Content Area.
			 */
			( new \CPR\Components\Column_Area() )
				->set_theme( 'oneColumn' )
				->set_config( 'heading', $this->wp_post_get_title() )
				->append_children(
					[
						( new \CPR\Components\Content\Newsletter_Content() )
							->set_post( $this->post ),
					]
				),
		];
	}

	/**
	 * Modify rewrite rules.
	 */
	public static function dispatch_rewrites() {
		\Alleypack\Path_Dispatch()->add_path(
			[
				'path'     => 'iframe-newsletter',
				'rewrite'  => [
					'rule'     => 'iframe/newsletter/(.*)/?',
					'redirect' => 'index.php?post_type=newsletter-single&name=$matches[1]',
				]
			]
		);
	}
}
