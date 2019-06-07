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
		$this->append_child(
			( new \CPR\Components\Content\Newsletter_Content() )
				->set_post( $this->post )
		);
		return $this;
	}
}
