<?php
/**
 * Error Template Component.
 *
 * @package CPR
 */

namespace CPR\Template;

/**
 * Error page template.
 */
class Error extends \WP_Component\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'error';

	/**
	 * Create template children.
	 *
	 * @return array
	 */
	public function default_children() {
		add_filter(
			'wp_irving_components_route_status',
			function( $status ) {
				return 404;
			}
		);

		return [
			new \WP_Component\Body(),
		];
	}
}
