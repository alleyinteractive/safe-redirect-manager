<?php
/**
 * Single Episode Template.
 *
 * @package Cpr
 */

namespace Cpr\Template;

/**
 * Single Episode routing and components for WP Irving.
 */
class Episode {

	/**
	 * Post object for this episode.
	 *
	 * @var null|\WP_Post
	 */
	public $post = null;

	/**
	 * WP_Query object for this request.
	 *
	 * @var null|\WP_Query
	 */
	public $wp_query = null;

	/**
	 * Return Irving components for Single Episode templates.
	 *
	 * @param  array     $data Response data.
	 * @param  \WP_Query $wp_query Path query.
	 * @return array Update response data.
	 */
	public function get_irving_components( array $data, \WP_Query $wp_query ) : array {

		$this->wp_query = $wp_query;

		// Get and validate the post object.
		$this->post = $wp_query->get_queried_object();
		if ( ! $this->post instanceof \WP_Post || 'publish' !== $this->post->post_status ) {
			return ( new \Cpr\Template\Error() )->get_irving_components( $data, $wp_query );
		}

		$data['page'][] = $this->get_head();
		$data['page'][] = $this->get_body();

		return $data;
	}

	/**
	 * Customize the head component.
	 *
	 * @return \WP_Irving\Component\Head
	 */
	public function get_head() {
		$component = ( new \WP_Irving\Component\Head() )
			->set_from_query( $this->wp_query );

		return $component;
	}

	/**
	 * Get the body component(s).
	 *
	 * @return \Cpr\Component\Body
	 */
	public function get_body() {
		// Initialize new Body component.
		$body = ( new \Cpr\Component\Body() )
			->set_config( 'classes', [ 'single' ] );

		return $body;
	}
}
