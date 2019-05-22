<?php
/**
 * Content Header Byline component.
 *
 * @package CPR
 */

namespace CPR\Components\Content;

/**
 * Content Byline class.
 */
class Header_Byline extends \WP_Components\Byline {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'content-header-byline';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'link'       => '',
			'name'       => '',
			'avatar_src' => '',
		];
	}

	/**
	 * Handling for Co-Authors Plus guest author objects.
	 *
	 * @return self
	 */
	public function guest_author_has_set() : \WP_Components\Byline {
		$this->set_config(
			'avatar_src',
			get_avatar_url( $this->guest_author->ID )
		);

		return parent::guest_author_has_set();
	}
}
