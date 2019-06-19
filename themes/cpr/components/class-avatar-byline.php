<?php
/**
 * Byline with an Avatar component.
 *
 * @package CPR
 */

namespace CPR\Components;

/**
 * Avatar Byline class.
 */
class Avatar_Byline extends \WP_Components\Byline {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'avatar-byline';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'pre_byline' => __( 'By ', 'cpr' ),
		];
	}

	/**
	 * Handling for Co-Authors Plus guest author objects.
	 *
	 * @return self
	 */
	public function guest_author_has_set() : \WP_Components\Byline {
		$this->guest_author_set_avatar( 'avatar' );

		return parent::guest_author_has_set();
	}
}
