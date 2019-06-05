<?php
/**
 * Error Template Component.
 *
 * @package CPR
 */

namespace CPR\Components\Templates;

/**
 * Error template.
 */
class Error extends \WP_Components\Component {

	use \WP_Components\WP_Query;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'error-template';

	/**
	 * Hook into query being set.
	 */
	public function query_has_set() {
		$body = new \WP_Components\Body();
		$body->children = array_filter( $this->get_components() );
		$this->append_child( $body );
		return $this;
	}

	/**
	 * Get an array of all components.
	 *
	 * @return array
	 */
	public function get_components() : array {
		return [
			( new \CPR\Components\Error_404() )
				->append_child(
					[
						( new \CPR\Components\Search\Search_Bar() )
							->set_theme( 'error' ),
					]
				),
		];
	}
}
