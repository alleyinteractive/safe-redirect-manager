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

	/**
	 * Hook into post being set.
	 */
	public function post_has_set() {
		$this->append_child( $this->get_body() );
		return $this;
	}

	public function get_body() {
		$body = new \Alleypack\WP_Component\Body();
		print_r($post); die();
		return $body;
	}

	public function get_page() {

	}

	public function get_landing_page() {

	}
}
