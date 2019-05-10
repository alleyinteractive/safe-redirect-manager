<?php
/**
 * Underwriter Directory component.
 *
 * @package CPR
 */

namespace CPR\Components\Underwriter;

/**
 * Underwriter directory.
 */
class Directory extends \WP_Components\Component {

	use \WP_Components\WP_Query;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'underwriter-directory';

	/**
	 * Hook into query being set.
	 *
	 * @return self
	 */
	public function query_has_set() : self {
		return $this;
	}
}
