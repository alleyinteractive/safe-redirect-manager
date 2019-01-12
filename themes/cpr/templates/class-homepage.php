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
	 * Hook into post being set.
	 */
	public function post_has_set() : array {
	}
}
