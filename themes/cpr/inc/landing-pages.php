<?php
/**
 * Ad integration
 *
 * @package CPR
 */

namespace CPR;

// Enable landing pages.
add_action( 'after_setup_theme', [ __NAMESPACE__ . '\Landing_Pages', 'instance' ] );

// Add fields.
add_filter( 'landing_page_fm_children', [ '\\CPR\\Components\\Templates\\Homepage', 'landing_page_fields' ] );
add_filter( 'landing_page_fm_children', [ '\\CPR\\Components\\Templates\\News', 'landing_page_fields' ] );
add_filter( 'landing_page_fm_children', [ '\\CPR\\Components\\Templates\\Classical', 'landing_page_fields' ] );
add_filter( 'landing_page_fm_children', [ '\\CPR\\Components\\Templates\\Openair', 'landing_page_fields' ] );

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
		'openair'   => [
			'label' => __( 'OpenAir', 'cpr' ),
			'slugs' => [
				'openair',
			],
		],
	];
}
add_filter( 'landing_page_options', __NAMESPACE__ . '\landing_page_options' );
