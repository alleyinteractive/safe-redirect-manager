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
			'size_mapping' => [
				[
					'viewport' => [ 1024, 0 ],
					'sizes'    => [ 300, 600 ],
				],
				[
					'viewport' => [ 0, 0 ],
					'sizes'    => [ 300, 250 ],
				],
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
		$section      = 'cpr';
		$sections     = [ 'cpr', 'news', 'indie', 'classical' ];
		$landing_page = $wp_query->query_vars['landing-page-type'];

		if ( ! empty( $landing_page ) && in_array( $landing_page, $sections, true ) ) {
			$section = $landing_page;
		}

		if ( $wp_query->is_singular() ) {
			$term_obj = get_the_terms( $wp_query->queried_object->ID, 'section' );
			$section  = is_array( $term_obj ) && in_array( $term_obj[0]->slug, $sections, true ) ? $term_obj[0]->slug : $section;
		}

		return $this->set_config(
			'targeting',
			[
				'section' => $section,
			]
		);
	}
}
