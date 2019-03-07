<?php
/**
 * Content Byline component.
 *
 * @package CPR
 */

namespace CPR\Components\Content;

/**
 * Content Byline class.
 */
class Byline extends \WP_Components\Byline {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'content-byline';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config(): array {
		return [
			'email'   => '',
			'link'    => '',
			'name'    => '',
			'twitter' => '',
		];
	}

	/**
	 * Handling for Co-Authors Plus guest author objects.
	 *
	 * @return self
	 */
	public function guest_author_has_set() : parent {
		$this->merge_config(
			[
				'email'       => $this->guest_author->user_email ?? '',
				'name'        => $this->guest_author->display_name ?? '',
				'link'        => get_author_posts_url( $this->guest_author->ID, $this->guest_author->user_nicename ),
				'twitter'     => get_post_meta( $this->guest_author->ID, 'twitter', true ),
			]
		);
		$this->guest_author_set_avatar( 'avatar' );
		$this->append_child(
			( new \WP_Components\HTML() )
				->set_config( 'content', get_post_meta( $this->guest_author->ID, 'description', true ) )
		);

		return $this;
	}
}
