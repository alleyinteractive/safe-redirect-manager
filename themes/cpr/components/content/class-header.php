<?php
/**
 * Content Header component.
 *
 * @package CPR
 */

namespace CPR\Components\Content;

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
	public function default_config() : array {
		return [
			'audio_length'  => '',
			'audio_url'     => '',
			'eyebrow_label' => '',
			'eyebrow_link'  => '',
			'image_size'    => 'content_single',
			'publish_date'  => '',
			'title'         => '',
		];
	}

	/**
	 * Fires after the post object has been set on this class.
	 *
	 * @return self
	 */
	public function post_has_set() : self {

		// Default settings.
		$this->wp_post_set_title();

		// Set configs and children based on post type.
		switch ( $this->post->post_type ?? '' ) {
			case 'post':
				// Configs.
				$this->set_eyebrow();
				$this->set_byline();
				$this->set_publish_date();
				$this->set_audio();

				// Featured image.
				if ( has_post_thumbnail( $this->get_post_id() ) ) {
					$this->wp_post_set_featured_image(
						$this->get_config( 'image_size' ),
						[ 'show_caption' => true ]
					);
				}

				// Children.
				$this->append_child( new \CPR\Components\Ad() );
				$this->append_child(
					( new \WP_Components\Social_Sharing() )
						->merge_config(
							[
								'services' => [
									'facebook' => true,
									'twitter'  => true,
									'email'    => true,
								],
								'text'     => __( 'Share: ', 'cpr' ),
							]
						)
						->set_post( $this->post )
				);
				break;

			case 'page':
				break;
		}

		return $this;
	}
}
