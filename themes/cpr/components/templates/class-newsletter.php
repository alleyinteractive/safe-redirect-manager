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
			 * Newsletter CTA.
			 */
			new \CPR\Components\Modules\Newsletter(),
		];
	}

	/**
	 * Modify rewrite rules.
	 */
	public static function dispatch_rewrites() {
		\Alleypack\Path_Dispatch()->add_path(
			[
				'path'    => 'iframe-newsletter',
				'rewrite' => [
					'rule'     => 'iframe/newsletter/(.*)/?',
					'redirect' => 'index.php?post_type=newsletter-single&name=$matches[1]',
				],
			]
		);
	}
}
