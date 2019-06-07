<?php
/**
 * Newsletter Content component.
 *
 * @package WP_Components
 */

namespace CPR\Components\Content;

/**
 * Newsletter Content.
 */
class Newsletter_Content extends \WP_Components\Component {

	use \CPR\WP_Post;
	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'newsletter-content';

	/**
	 * Fires after the post object has been set on this class.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		$this->append_child(
			( new \WP_Components\HTML() )
				->set_config(
					'content',
					get_post_meta( $this->get_post_id(), 'newsletter_html', true )
				)
		);

		return $this;
	}
}
