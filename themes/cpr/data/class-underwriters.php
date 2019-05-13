<?php
/**
 * Class to expose underwriter data to the Irving data endpoint.
 *
 * @package CPR;
 */

namespace CPR\Data;

/**
 * Underwriters data endpoint.
 */
class Underwriters {

	use \Alleypack\Singleton;

	/**
	 * The underwriter data for async loading.
	 *
	 * @var array
	 */
	public $data = [];

	/**
	 * Get the data endpoint settings.
	 *
	 * @return array
	 */
	public function get_endpoint_settings() : array {
		return [
			'slug'     => 'underwriters',
			'callback' => [ $this, 'get_data' ],
		];
	}

	/**
	 * Get the data for this endpoint.
	 *
	 * @return array
	 */
	public function get_data() : array {
		if ( ! empty( $this->data ) ) {
			return $this->data;
		}

		$underwriter_query = new \WP_Query(
			[
				'post_type'      => 'underwriter',
				'posts_per_page' => 1000,
			]
		);

		if ( ! $underwriter_query instanceof \WP_Error && ! empty( $underwriter_query->posts ) ) {
			$this->data = array_map( [ $this, 'get_data_from_post' ], $underwriter_query->posts );
		}

		return $this->data;
	}

	/**
	 * Convert a WP_Post into the underwriter data format.
	 *
	 * @param \WP_Post $post Underwriter post object.
	 * @return array
	 */
	public function get_data_from_post( \WP_Post $post ) : array {
		return [
			'id'           => $post->ID,
			'name'         => get_the_title( $post ),
			'link'         => (string) esc_url( get_post_meta( $post->ID, 'link', true ) ),
			'phone_number' => (string) get_post_meta( $post->ID, 'phone_number', true ),
			'address'      => (string) get_post_meta( $post->ID, 'address', true ),
		];
	}
}

// Expose underwriter data to endpoint.
add_filter(
	'wp_irving_data_endpoints',
	function( $endpoints ) {
		$endpoints[] = \CPR\Data\Underwriters::instance()->get_endpoint_settings();
		return $endpoints;
	}
);
