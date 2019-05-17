<?php
/**
 * Show and Podcast Header component.
 *
 * @package CPR
 */

namespace CPR\Components\Podcast_And_Show;

/**
 * Show and Podcast Header
 */
class Header extends \WP_Components\Component {

	use \WP_Components\WP_Post;
	use \CPR\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'show-and-podcast-header';

	/**
	 * Hook into post being set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {

		$this->append_child(
			( new \CPR\Components\Content\Title() )
				->merge_config(
					[
						'content' => $this->wp_post_get_title(),
						'link'    => $this->wp_post_get_permalink(),
					]
				)
		);

		$this->append_child(
			( new \WP_Components\Component() )
				->set_name( 'excerpt' )
				->set_config( 'content', $this->wp_post_get_excerpt() )
		);

		$this->set_eyebrow();

		$this->wp_post_set_featured_image( 'show-and-podcast-header' );

		return $this;
	}
}
