<?php
/**
 * Top 30 component.
 *
 * @package CPR
 */

namespace CPR\Components\Audio;

/**
 * Top 30.
 */
class Top_30 extends \WP_Components\Component {

	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'top-30';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'cta_label' => __( 'All Top 30', 'cpr' ),
			'cta_link'  => get_post_type_archive_link( 'top-30' ),
		];
	}

	/**
	 * Set from the latest Top 30 post.
	 *
	 * @return self
	 */
	public function set_from_latest() : self {
		$query = new \WP_Query(
			[
				'post_type'      => 'top-30',
				'posts_per_page' => 1,
			]
		);

		if ( ! empty( $query->posts[0] ) ) {
			$this->set_post( $query->posts[0] );
		}
		
		return $this;
	}

	/**
	 * Fires after the post object has been set on this class.
	 *
	 * @return self
	 */
	public function post_has_set() : self {

		// Create child for the heading.
		$this->append_child(
			( new \WP_Components\HTML() )
				->set_config(
					'content',
					sprintf(
						'<a href="%1$s">%2$s</a> / %3$s',
						esc_url( home_url( '/top-30/' ) ),
						__( 'Top 30', 'cpr' ),
						$this->wp_post_get_title()
					)
				)
		);

		// Get the albums.
		$album_ids = (array) get_post_meta( $this->get_post_id(), 'album_ids', true );

		// Create album children.
		foreach ( $album_ids as $album_id ) {
			$this->append_child(
				( new Album() )
					->set_post( $album_id )
			);
		}

		return $this;
	}
}
