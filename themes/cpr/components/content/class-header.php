<?php
/**
 * Content Header component.
 *
 * @package CPR
 */

namespace CPR\Component\Content;

/**
 * Content Header class.
 */
class Header extends \WP_Components\Component {

	use \CPR\WP_Post;
	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'content-header';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() {
		return [
			'image_size' => 'featured-media',
		];
	}

	public function default_children() {
		return [
			new \CPR\Component\Ad(),
		];
	}

	public function post_has_set() {
		$this->set_title();
		$this->set_eyebrow();
		$this->set_byline();
		$this->set_publish_date();
		$this->set_audio();
		$this->set_featured_image( $this->get_config( 'image_size' ) );
		$this->merge_config(
			[
				'permalink' => get_permalink( $this->post->ID ),
				'type'      => $this->post->post_type ?? 'post',
			]
		);
	}
}
