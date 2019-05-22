<?php
/**
 * Content_Title component.
 *
 * @package CPR
 */

namespace CPR\Components\Content;

/**
 * Content_Title.
 */
class Content_Title extends \WP_Components\Component {

	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'content-title';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'content'    => '',
			'link'       => '',
			'theme_name' => 'grid',
		];
	}

	/**
	 * Fires after the post object has been set on this class.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		$this->merge_config(
			[
				'content' => $this->wp_post_get_title(),
				'link'    => $this->wp_post_get_permalink(),
			]
		);

		return $this;
	}
}
