<?php
/**
 * Term Template.
 *
 * @package Cpr
 */

namespace Cpr\Template;

/**
 * Term routing and components for WP Irving.
 */
class Term {

	/**
	 * The term being rendered.
	 *
	 * @var null
	 */
	public $term = null;

	/**
	 * The posts retrieved by this term query.
	 *
	 * @var array
	 */
	public $posts = [];

	/**
	 * Return Irving components for Term templates.
	 *
	 * @param  array     $data     Response data.
	 * @param  \WP_Query $wp_query Path query.
	 * @return array               Updated response data.
	 */
	public function get_irving_components( array $data, \WP_Query $wp_query ) : array {

		// Get term.
		$term = $wp_query->get_queried_object();

		// Return error if we don't have a term object.
		if ( ! $term instanceof \WP_Term ) {
			return ( new \Cpr\Template\Error() )->get_irving_components( $data, $wp_query );
		}

		// Store term and posts.
		$this->term  = $term;
		$this->posts = $wp_query->posts;

		// Get body.
		$data['page'][] = $this->get_body();

		return $data;
	}

	/**
	 * Build the body component.
	 *
	 * @return Body
	 */
	public function get_body() {
		return ( new \Cpr\Component\Body() );
	}
}
