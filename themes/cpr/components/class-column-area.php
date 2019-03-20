<?php
/**
 * Column_Area component.
 *
 * @package CPR
 */

namespace CPR\Components;

/**
 * Column_Area.
 */
class Column_Area extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'column-area';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'theme_name'        => 'threeColumn',
			'heading'           => '',
			'heading_link'      => '',
			'heading_cta_label' => '',
			'heading_cta_link'  => '',
		];
	}

	/**
	 * Set content list heading from FM data.
	 *
	 * @param array $fm_data Stored Fieldmanager data.
	 * @return self
	 */
	public function set_heading_from_fm_data( $fm_data ) : self {
		return $this->merge_config(
			[
				'heading'      => (string) ( $fm_data['heading'] ?? '' ),
				'heading_link' => (string) ( $fm_data['heading_link'] ?? '' ),
			]
		);
	}
}
