<?php
/**
 * Underwriter Corporate Partners component.
 *
 * @package CPR
 */

namespace CPR\Components\Underwriter;

/**
 * Underwriter Corporate Partners.
 */
class Corporate_Partners extends \WP_Components\Component {

	use \WP_Components\WP_Query;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'underwriter-corporate-partners';

	/**
	 * Hook into query being set.
	 *
	 * @return self
	 */
	public function query_has_set() : self {
		$children = array_map(
			function( $underwriter ) {
				return ( new Corporate_Partner_Item() )->set_post( $underwriter );
			},
			$this->get_posts()
		);

		$this->set_children( $children );
		return $this;
	}
}
