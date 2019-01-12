<?php
/**
 * Homepage Template.
 *
 * @package CPR
 */

namespace CPR\Template;

/**
 * Homepage template.
 */
class Homepage extends \Alleypack\WP_Component\Template {

	use \Alleypack\WP_Component\WP_Post;

	/**
	 * WP Query for this request.
	 *
	 * @var null|WP_Query
	 */
	public $wp_query = null;

	/**
	 * Return Irving components for Homepage templates.
	 *
	 * @param  array     $data     Response data.
	 * @param  \WP_Query $wp_query Path query.
	 * @return array               Updated response data.
	 */
	public function get_irving_components( array $data, \WP_Query $wp_query ) : array {

		$this->wp_query = $wp_query;

		return $data;
	}
}
