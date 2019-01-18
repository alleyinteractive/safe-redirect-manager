<?php
/**
 * Header Sections component.
 *
 * @package CPR
 */

namespace CPR\Component\Header;

/**
 * Header Sections.
 */
class Sections extends \WP_Component\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'header-sections';

	/**
	 * Default config is the sections.
	 *
	 * @return array
	 */
	public function default_config() {
		return [
			'sections' => [
				[
					'label' => __( 'News', 'cpr' ),
					'link'  => home_url( '/news/' ),
				],
				[
					'label' => __( 'Classical', 'cpr' ),
					'link'  => home_url( '/classical/' ),
				],
				[
					'label' => __( 'OpenAir', 'cpr' ),
					'link'  => home_url( '/openair/' ),
				],
			]
		];
	}
}
