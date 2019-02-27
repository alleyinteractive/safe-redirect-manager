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
	 */
	public function post_has_set() {

		// Default settings.
		$this->wp_post_set_title();

		// Set configs and children based on post type.
		switch ( $this->post->post_type ?? '' ) {
			case 'post':
				$this->set_eyebrow();
				$this->set_byline();
				$this->set_publish_date();
				$this->set_audio();
				$this->wp_post_set_featured_image( $this->get_config( 'image_size' ) );
				$this->append_child( new \CPR\Component\Ad() );
				$this->append_child(
					( new \WP_Components\Social_Sharing() )
						->merge_config(
							[
								'services' => [
									'facebook' => true,
									'twitter'  => true,
									'email'    => true,
								],
							]
						)
						->set_post( $this->post )
				);
				break;

			case 'page':
				break;
		}
	}
}
