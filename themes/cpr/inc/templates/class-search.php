<?php
/**
 * Search Template.
 *
 * @package CPR
 */

namespace Cpr\Template;

/**
 * Search routing and components for WP Irving.
 */
class Search {

	/**
	 * WP Query for this request.
	 *
	 * @var null|WP_Query
	 */
	public $wp_query = null;

	/**
	 * The string being searched for.
	 *
	 * @var string
	 */
	public $search_string = '';

	/**
	 * Return Irving components for Search templates.
	 *
	 * @param  array     $data     Response data.
	 * @param  \WP_Query $wp_query Path query.
	 * @return array               Updated response data.
	 */
	public function get_irving_components( array $data, \WP_Query $wp_query ) : array {

		$this->wp_query = $wp_query;
		$this->search_string = $wp_query->get( 's' );

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
		return ( new \Colorado_Public_Radio\Component\Body() );
	}
}
