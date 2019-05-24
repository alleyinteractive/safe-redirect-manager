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
	 * Show and Podcast Header default config.
	 *
	 * @return array
	 */
	public function default_config() : array {
		return [
			'times'     => '',
			'buttons' => [],
		];
	}

	/**
	 * Hook into post being set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		$description = get_post_meta( $this->get_post_id(), 'description', true );
		$subscribe   = get_post_meta( $this->get_post_id(), 'subscribe', true );
		$times       = get_post_meta( $this->get_post_id(), 'times', true );

		$this->merge_config(
			[
				'times'     => $times,
				'buttons' => $subscribe['buttons'],
			]
		);

		$this->append_child(
			( new \CPR\Components\Content\Content_Title() )
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
				->set_config( 'content', $description )
		);

		$this->set_eyebrow();

		$this->wp_post_set_featured_image( 'show-and-podcast-header' );

		return $this;
	}
}
