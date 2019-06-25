<?php
/**
 * Component for providing data to ad tags.
 *
 * @package CPR
 */

namespace CPR\Components\Advertising;

/**
 * Ad Provider.
 */
class Ad_Provider extends \WP_Components\Component {

	/**
	 * Unique component slug.
	 *
	 * @var string
	 */
	public $name = 'ad-provider';

	/**
	 * Configurations for various ad slots.
	 *
	 * @var string
	 */
	public static $slot_configs = [
		'CPR3-Inst-News-Shared-300x600' => [
			'sizes' => [
				[ 300, 250 ],
				[ 300, 600 ],
			],
		],
		'CPR3-Combined-300x250' => [
			'sizes' => [ [ 300, 250 ] ],
		],
		'CPR3-Article-300x250' => [
			'sizes' => [ [ 300, 250 ] ],
		],
	];

	/**
	 * Define a default config shape.
	 *
	 * @return array Default config.
	 */
	public function default_config() : array {
		return [
			'targeting'      => [],
			'dfp_network_id' => '',
			'size_mapping'   => [
				[
					'viewport' => [ 768, 0 ],
					'sizes'    => [ 300, 600 ],
				],
				[
					'viewport' => [ 0, 0 ],
					'sizes'    => [ 300, 250 ],
				],
			],
		];
	}

	/**
	 * Set targeting arguments from wp_query.
	 *
	 * @todo make this do something.
	 * @param \WP_Query $wp_query WP_Query object.
	 * @return Ad_Provider
	 */
	public function set_targeting_from_query( $wp_query ) : self {
		return $this->set_config(
			'targeting',
			[
				'section' => '',
			]
		);
	}
}
