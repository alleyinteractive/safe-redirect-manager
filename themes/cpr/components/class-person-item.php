<?php
/**
 * Person Item component.
 *
 * @package CPR
 */

namespace CPR\Component;

/**
 * Person Item.
 */
class Person_Item extends \WP_Components\Component {

	use \WP_Components\Guest_Author;

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'person-item';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'heading'    => '',
			'image_size' => 'grid-item',
			'permalink'  => '',
			'subheading' => '',
			'theme'      => 'grid',
		];
	}

	/**
	 * Set component properties after author has been validated and set.
	 */
	public function guest_author_has_set() {
		// @todo Determine why this breaks things on the Irving side and fix it.
		// $this->set_guest_author_avatar( $this->get_config( 'image_size' ) );

		$this->merge_config(
			[
				'heading'   => $this->guest_author->post_title ?? '',
				'permalink' => get_permalink( $this->get_guest_author_id() ),
			]
		);
	}
}
