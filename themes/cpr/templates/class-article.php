<?php
/**
 * Article Template Component.
 *
 * @package CPR
 */

namespace CPR\Template;

/**
 * Article template.
 */
class Article extends \WP_Component\Component {

	use \WP_Component\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'article';

	/**
	 * Hook into post being set.
	 */
	public function post_has_set() {
	}
}
