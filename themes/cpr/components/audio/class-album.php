<?php
/**
 * Album component.
 *
 * @package CPR
 */

namespace CPR\Components\Audio;

/**
 * Album.
 */
class Album extends \WP_Components\Component {

	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'album';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'artist'   => '',
			'label'    => '',
			'position' => '',
			'title'    => '',
			'year'     => '',
		];
	}

	/**
	 * Fires after the post object has been set on this class.
	 *
	 * @return self
	 */
	public function post_has_set() : self {

		$this->wp_post_set_title();

		// Create child for the album cover image.
		$this->wp_post_set_featured_image( 'album_cover' );

		// Get the terms.
		$artists = (array) wp_get_post_terms( $this->get_post_id(), 'artist' );
		$labels  = (array) wp_get_post_terms( $this->get_post_id(), 'label' );

		$this->merge_config(
			[
				'artist' => $artists[0]->name ?? '',
				'label'  => $labels[0]->name ?? '',
				'year'   => get_post_meta( $this->get_post_id(), 'year', true ),
			]
		);

		return $this;
	}
}
