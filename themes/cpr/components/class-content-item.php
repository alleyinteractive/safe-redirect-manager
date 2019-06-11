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
			'audio_length' => '',
			'image_size'   => 'grid-item',
			'permalink'    => '',
			'show_excerpt' => false,
			'theme_name'   => 'grid',
			'type'         => '',
		];
	}

	/**
	 * Set component properties after a post object has been validated and set.
	 *
	 * @return self
	 */
	public function post_has_set() : self {
		$this->append_child(
			( new \CPR\Components\Content\Content_Title() )
				->merge_config(
					[
						'content' => $this->wp_post_get_title(),
						'link'    => $this->wp_post_get_permalink(),
					]
				)
		);

		// Get excerpt if required.
		if ( $this->get_config( 'show_excerpt' ) ) {
			$this->append_child(
				( new \WP_Components\Component() )
					->set_name( 'excerpt' )
					->set_config( 'content', $this->wp_post_get_excerpt() )
			);
		}

		$this->set_eyebrow();

		// Set audio if applicable.
		if ( 'podcast-episode' === ( $this->post->post_type ?? '' ) ) {
			$audio_meta = $this->get_audio_metadata();

			if ( ! empty( $audio_meta['src'] ) ) {
				$this->set_config( 'audio_length', $audio_meta['duration'] );

				$this->append_child(
					( new \CPR\Components\Audio\Play_Pause_Button() )
						->merge_config(
							[
								'src'           => $audio_meta['src'],
								'loadingHeight' => 12,
								'loadingWidth'  => 12,
								'title'         => [
									$audio_meta['title'],
									$audio_meta['artist'],
									$audio_meta['album'],
								],
							]
						)
						->set_theme( 'inline' )
				);
			}
		}

		$this->set_byline();
		$this->wp_post_set_featured_image( $this->get_config( 'image_size' ) );
		$this->merge_config(
			[
				'permalink' => get_permalink( $this->post->ID ?? 0 ),
			]
		);
		return $this;
	}
}
