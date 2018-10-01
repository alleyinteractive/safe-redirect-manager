<?php
/**
 * Ad integration
 *
 * @package Cpr
 */

namespace Cpr;

// Enable landing pages.
add_action( 'after_setup_theme', [ __NAMESPACE__ . '\Landing_Pages', 'instance' ] );

// Use `page` post type as the landing page.
add_filter(
	'landing_page_post_type',
	function( $post_type ) {
		return 'page';
	}
);

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
			'label' => __( 'Open Air', 'cpr' ),
			'slugs' => [
				'openair',
			],
		],
	];
}
add_filter( 'landing_page_options', __NAMESPACE__ . '\landing_page_options' );

/**
 * Add additional FM fields to a landing page.
 *
 * @param  array $fields FM fields.
 * @return array
 */
function landing_page_fields( $fields ) {
	$fields['classical'] = new \Fieldmanager_Group(
		[
			'label'      => __( 'Classical', 'cpr' ),
			'display_if' => [
				'src'   => 'landing_page_type',
				'value' => 'classical',
			],
			'children' => [
				'test' => new \Fieldmanager_Textfield( __( 'Placeholder field', 'cpr' ) ),
			],
		]
	);
	return $fields;
}
add_filter( 'landing_page_fm_children', __NAMESPACE__ . '\landing_page_fields' );
