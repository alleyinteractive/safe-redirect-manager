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
			'image_size'       => 'grid-item',
			'permalink'        => '',
			'theme'            => 'grid',
			'title'            => '',
			'type'             => '',
		];
	}

	/**
	 * Set component properties after a post object has been validated and set.
	 */
	public function post_has_set() {
		$this->set_title();
		$this->set_eyebrow();
		$this->set_byline();
		$this->set_featured_image( $this->get_config( 'image_size' ) );
		$this->merge_config(
			[
				'permalink' => get_permalink( $this->post->ID ),
				'type'      => $this->post->post_type ?? 'post',
			]
		);
	}
}
