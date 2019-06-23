<?php
/**
 * Advertisement component.
 *
 * @package CPR
 */

namespace CPR\Components\Advertising;

/**
 * Advertisement.
 */
class Ad_Unit extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'ad-unit';

	/**
	 * Define a default config.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'ad_unit'      => '',
			'sizes'        => [],
			'size_mapping' => [],
		];
	}

	/**
	 * Configure slot from a given slot ID.
	 *
	 * @param string $slot_id ID for a configured ad slot.
	 * @return self Default config.
	 */
	public function configure_ad_slot( $slot_id ) : self {
		$slot_config = Ad_Provider::$slot_configs[ $slot_id ];

		if ( ! empty( $slot_config ) ) {
			return $this->merge_config(
				array_merge(
					[ 'ad_unit' => $slot_id ],
					$slot_config
				)
			);
		}

		return $this;
	}
}
