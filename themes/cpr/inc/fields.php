<?php
/**
 * Fieldmanager fields
 *
 * @package Cpr
 */


/* begin fm:post-podcast-post-section */
/**
 * `post-podcast-post-section` Fieldmanager fields.
 */
function cpr_fm_post_podcast_post_section() {
	$fm = new Fieldmanager_Group(
		[
			'name' => 'post-podcast-post-section',
			'serialize_data' => false,
			'add_to_prefix' => false,
			'children' => [
				'section_term_id' => new Fieldmanager_Select(
					[
						'first_empty' => true,
						'datasource' => new Fieldmanager_Datasource_Term(
							[
								'taxonomy' => 'section',
								'taxonomy_save_to_terms' => true,
								'only_save_to_taxonomy' => false,
							]
						),
					]
				),
			],
		]
	);
	$fm->add_meta_box( __( 'Section', 'cpr' ), [ 'podcast-post' ], 'side' );
}
add_action( 'fm_post_podcast-post', 'cpr_fm_post_podcast_post_section' );
/* end fm:post-podcast-post-section */

/* begin fm:submenu-analytics-settings */
/**
 * `analytics_settings` Fieldmanager fields.
 */
function cpr_fm_submenu_analytics_settings() {
	$fm = new Fieldmanager_Group(
		[
			'name' => 'analytics_settings',
			'children' => [
				'`\WP_Irving\Components\Parsely::$option_field`' => new Fieldmanager_TextField( __( 'Parse.ly Site (e.g. cpr.org)', 'cpr' ) ),
			],
		]
	);
	$fm->activate_submenu_page();
}
add_action( 'fm_submenu_analytics_settings', 'cpr_fm_submenu_analytics_settings' );
if ( function_exists( 'fm_register_submenu_page' ) ) {
	fm_register_submenu_page( 'analytics_settings', 'options-general.php', __( 'Analytics', 'cpr' ), __( 'Analytics', 'cpr' ), 'manage_options' );
}
/* end fm:submenu-analytics-settings */
