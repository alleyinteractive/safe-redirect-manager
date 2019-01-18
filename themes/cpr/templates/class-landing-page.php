<?php
/**
 * Landing Page Template Component.
 *
 * @package CPR
 */

namespace CPR\Template;

/**
 * Landing Page template.
 */
class Landing_Page extends WP_Component\Component {

	use \Alleypack\WP_Component\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'landing-page';

	/**
	 * Hook into post being set.
	 */
	public function post_has_set() {
		$body = new \Alleypack\WP_Component\Body();

		// Set the children based on landing page type.
		$type = get_post_meta( $this->post->ID, 'landing_page_type', true );
		if ( method_exists( $this, $type ) ) {
			$body->set_children( $this->$type() );
		}

		$this->append_child( $body );
		return $this;
	}

	/**
	 * Define components for the homepage.
	 * @return array
	 */
	public function homepage() {
		return [];
	}
}
