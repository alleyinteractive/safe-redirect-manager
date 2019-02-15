<?php
/**
 * Content Footer component.
 *
 * @package CPR
 */

namespace CPR\Component\Content;

/**
 * Content Footer class.
 */
class Footer extends \WP_Components\Component {

	use \CPR\WP_Post;
	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'content-footer';
}
