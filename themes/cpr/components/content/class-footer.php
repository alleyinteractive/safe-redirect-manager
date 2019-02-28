<?php
/**
 * Content Footer component.
 *
 * @package CPR
 */

namespace CPR\Components\Content;

/**
 * Content Footer class.
 */
class Footer extends \WP_Components\Component {

	use \CPR\WP_Post;
	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'content-footer';

	/**
	 * Fires after the post object has been set on this class.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		$this->append_children(
			[
				( new Keep_Reading() )->set_post( $this->post ),
				( new Related_Tags() )->set_post( $this->post ),
				new \CPR\Components\Donate\Donate_CTA(),
				new Comments(),
			]
		);
		return $this;
	}
}
