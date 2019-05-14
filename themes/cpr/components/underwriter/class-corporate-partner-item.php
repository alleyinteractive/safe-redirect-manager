<?php
/**
 * Underwriter Corporate Partner Item component.
 *
 * @package CPR
 */

namespace CPR\Components\Underwriter;

/**
 * Underwriter Corporate Partner Item.
 */
class Corporate_Partner_Item extends \WP_Components\Component {

	use \WP_Components\WP_Post;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'underwriter-corporate-partner-item';

	/**
	 * nderwriter Corporate Partner Item default config.
	 *
	 * @return array
	 */
	public function default_config() : array {
		return [
			'url' => '',
			'title' => '',
		];
	}

	public function post_has_set() : self {
		$this->set_config(
			'url',
			get_post_meta( $this->get_post_id(), 'link', true )
		);

		if( has_post_thumbnail( $this->get_post_id() ) ) {
			$this->wp_post_set_featured_image( 'underwriter' );
		}

		$this->wp_post_set_title();

		return $this;
	}
}
