<?php
/**
 * Content Item component.
 *
 * @package CPR
 */

namespace CPR\Components;

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
			'audio_url'     => '',
			'audio_length'  => '',
			'image_size'    => 'grid-item',
			'permalink'     => '',
			'show_excerpt'  => false,
			'theme_name'    => 'gridPrimary',
			'type'          => '',
		];
	}

	/**
	 * Set component properties after a post object has been validated and set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		$this->append_child(
			( new \CPR\Components\Content\Title() )
				->merge_config(
					[
						'content' => $this->wp_post_get_title(),
						'link'    => get_permalink( $this->post->ID ),
					]
				)
		);

		// Get excerpt if required.
		if ( $this->get_config( 'show_exceprt' ) ) {
			$this->append_child(
				( new \WP_Components\Component() )
					->set_name( 'excerpt' )
					->set_config( 'content', $this->wp_post_get_excerpt() )
			);
		}

		$this->set_eyebrow();

		// Set audio if applicable.
		if ( 'podcast-episode' === $this->post->post_type ) {
			$this->set_audio();
		}

		$this->set_byline();
		$this->wp_post_set_featured_image( $this->get_config( 'image_size' ) );
		$this->merge_config(
			[
				'permalink' => get_permalink( $this->post->ID ),
			]
		);
		return $this;
	}
}
