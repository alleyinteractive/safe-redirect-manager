<?php
/**
 * Ad integration
 *
 * @package CPR
 */

namespace CPR;

// Add fields.
add_filter( 'landing_page_fm_children', [ '\\CPR\\Components\\Templates\\Homepage', 'landing_page_fields' ] );
add_filter( 'landing_page_fm_children', [ '\\CPR\\Components\\Templates\\News', 'landing_page_fields' ] );
add_filter( 'landing_page_fm_children', [ '\\CPR\\Components\\Templates\\Classical', 'landing_page_fields' ] );
add_filter( 'landing_page_fm_children', [ '\\CPR\\Components\\Templates\\Indie', 'landing_page_fields' ] );

/**
 * Setup landing page options.
 *
 * @param  array $options Options.
 * @return array
 */
function landing_page_options( $options ) {
	return [
		'homepage'  => [
			'label' => __( 'Homepage', 'cpr' ),
			'slugs' => [
				'/',
			],
		],
		'news'      => [
			'label' => __( 'News', 'cpr' ),
			'slugs' => [
				'news',
			],
		],
		'classical' => [
			'label' => __( 'Classical', 'cpr' ),
			'slugs' => [
				'classical',
			],
		],
		'indie'   => [
			'label' => __( 'Indie', 'cpr' ),
			'slugs' => [
				'indie',
			],
		],
	];
}
add_filter( 'landing_page_options', __NAMESPACE__ . '\landing_page_options' );
