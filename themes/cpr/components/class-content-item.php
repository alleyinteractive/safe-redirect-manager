<?php
/**
 * Content Item component.
 *
 * @package CPR
 */

namespace CPR\Component;

/**
 * Content Item.
 */
class Content_Item extends \WP_Components\Component {

	use \WP_Components\WP_Post;
	use \CPR\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'content-item';

	/**
	 * Content item default config.
	 *
	 * @return array
	 */
	public function default_config() : array {
		return [
			'eyebrow_label'    => '',
			'eyebrow_link'     => '',
			'eyebrow_location' => 'bottom',
			'title'            => '',
		];
	}

	/**
	 * Set component properties after a post object has been validated and set.
	 */
	public function post_has_set() {
		$this->set_title();
		$this->set_eyebrow();
		$this->set_byline();
	}
}
