<?php
/**
 * Page Template Component.
 *
 * @package CPR
 */

namespace CPR\Template;

/**
 * Page template.
 */
class Page extends \Alleypack\WP_Component\Component {

	use \Alleypack\WP_Component\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'page';
}
