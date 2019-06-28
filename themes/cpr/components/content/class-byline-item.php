<?php
/**
 * Content Bylines component.
 *
 * @package CPR
 */

namespace CPR\Components\Content;

/**
 * Content Bylines class.
 */
class Byline_Item extends \WP_Components\Byline {

	use \WP_Components\WP_Post;
	use \WP_Components\WP_User;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'content-byline-item';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'email'   => '',
			'name'    => '',
			'link'    => '',
			'twitter' => '',
		];
	}

	/**
	 * Fires after the post object has been set on this class.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		// Setup byline using guest authors.
		$coauthor = get_coauthor( $this->post->ID );

		$this->append_child(
			( new \WP_Components\Component() )
				->set_name( 'byline-item' )
				->merge_config(
					[
						'email'   => $coauthor->user_email ?? '',
						'name'    => $coauthor->display_name ?? '',
						'link'    => get_author_posts_url( $coauthor->ID, $coauthor->user_nicename ),
						'twitter' => get_post_meta( $coauthor->ID, 'twitter', true ),
					]
				)
		);

		return $this;
	}
}
