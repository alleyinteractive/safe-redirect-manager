<?php
/**
 * Grid Group Item.
 *
 * @package CPR
 */

namespace CPR\Components\Modules;

/**
 * Grid group item.
 */
class Grid_Group_Item extends \WP_Components\Component {

	use \Alleypack\FM_Module;
	use \WP_Components\WP_Post;
	use \CPR\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'grid-group-item';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'permalink' => '',
			'role'      => '',
			'show_name' => '',
		];
	}

	/**
	 * Hook into post being set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {

		$this->wp_post_set_permalink();

		if ( 'guest-author' === $this->post->post_type ) {
			$author_url = get_author_posts_url( $this->get_post_id(), $this->post->post_name );
			$author_url = str_replace( 'cap-', '', $author_url );

			$this->wp_post_set_featured_image( 'grid-group-host' );
			$this->set_config( 'role', get_post_meta( $this->get_post_id(), 'title', true ) );
			$this->set_config( 'permalink', $author_url );
		} else {
			$this->wp_post_set_featured_image( 'grid-group-item' );
			$this->append_child(
				( new \WP_Components\Component() )
					->set_name( 'excerpt' )
					->callback(
						function( $component ) {
							if ( 'external-link' === $this->post->post_type ) {
								$component->set_config( 'content', strip_tags( $this->post->post_content ) );
							} else {
								$component->set_config( 'content', get_post_meta( $this->get_post_id(), 'teaser', true ) );
							}
							return $component;
						}
					)
			);
		}

		$this->append_child(
			( new \CPR\Components\Content\Content_Title() )
				->merge_config(
					[
						'content' => $this->wp_post_get_title(),
						'link'    => $author_url ?? $this->wp_post_get_permalink(),
					]
				)
		);

		return $this;
	}
}
