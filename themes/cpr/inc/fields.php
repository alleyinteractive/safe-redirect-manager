<?php
/**
 * Fieldmanager fields
 *
 * @package CPR
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
	$fm->add_meta_box( __( 'Section', 'cpr' ), [ 'post', 'podcast-post', 'event' ], 'side' );
}
add_action( 'fm_post_post', 'cpr_fm_post_podcast_post_section' );
add_action( 'fm_post_podcast-post', 'cpr_fm_post_podcast_post_section' );
add_action( 'fm_post_event', 'cpr_fm_post_podcast_post_section' );
/* end fm:post-podcast-post-section */

/* begin fm:submenu-settings */
/**
 * `cpr-settings` Fieldmanager fields.
 */
function cpr_fm_submenu_settings() {
	$fm = new Fieldmanager_Group(
		[
			'name' => 'cpr-settings',
			'tabbed' => 'vertical',
			'children' => [
				'analytics' => new Fieldmanager_Group(
					[
						'label' => __( 'Analytics', 'cpr' ),
						'children' => [
							'parsely_site' => new Fieldmanager_TextField( __( 'Parse.ly Site (e.g. cpr.org)', 'cpr' ) ),
						],
					]
				),
				'giving' => new Fieldmanager_Group(
					[
						'label' => __( 'Giving', 'cpr' ),
						'children' => [
							'donate' => new Fieldmanager_Group(
								[
									'children' => ( new \CPR\Component\Donate_Button() )->get_fm_fields(),
								]
							),
						],
					]
				),
				'engagement' => new Fieldmanager_Group(
					[
						'label' => __( 'Engagement', 'cpr' ),
						'children' => [
							'forum_shortname' => new Fieldmanager_TextField( __( 'Disqus Forum Shortname', 'cpr' ) ),
							'newsletter' => new Fieldmanager_Group(
								[
									'label' => __( 'Newsletter Settings', 'cpr' ),
									'children' => ( new \CPR\Component\Modules\Newsletter() )->get_fm_fields(),
								]
							),
						],
					]
				),
				'social' => new Fieldmanager_Group(
					[
						'label' => __( 'Social', 'cpr' ),
						'children' => [
							'social' => new Fieldmanager_Group(
								[
									'label' => __( 'Social Settings', 'cpr' ),
									'children' => ( new \CPR\Component\Social_Links() )->get_fm_fields(),
								]
							),
						],
					]
				),
			],
		]
	);
	$fm->activate_submenu_page();
}
add_action( 'fm_submenu_cpr-settings', 'cpr_fm_submenu_settings' );
if ( function_exists( 'fm_register_submenu_page' ) ) {
	fm_register_submenu_page( 'cpr-settings', 'options-general.php', __( 'CPR Settings', 'cpr' ), __( 'CPR Settings', 'cpr' ), 'manage_options' );
}
/* end fm:submenu-settings */



/* begin fm:post-event-settings */
/**
 * `post-event-settings` Fieldmanager fields.
 */
function cpr_fm_post_event_settings() {
	$fm = new Fieldmanager_Group(
		[
			'name' => 'post-event-settings',
			'serialize_data' => false,
			'add_to_prefix' => false,
			'tabbed' => 'vertical',
			'children' => [
				'settings' => new Fieldmanager_Group(
					[
						'label' => __( 'Settings', 'cpr' ),
						'serialize_data' => false,
						'add_to_prefix' => false,
						'children' => [
							'section_id' => new Fieldmanager_Select(
								[
									'label' => __( 'Section', 'cpr' ),
									'description' => __( 'Select a section.', 'cpr' ),
									'datasource' => new Fieldmanager_Datasource_Term(
										[
											'taxonomy' => 'section',
											'taxonomy_save_to_terms' => true,
											'only_save_to_taxonomy' => true,
										]
									),
								]
							),
						],
					]
				),
				'event_details' => new Fieldmanager_Group(
					[
						'label' => __( 'Event Details', 'cpr' ),
						'serialize_data' => false,
						'add_to_prefix' => false,
						'children' => [
							'start_datetime' => new Fieldmanager_Datepicker(
								[
									'label' => __( 'Start Date', 'cpr' ),
									'use_am_pm' => true,
									'use_time' => true,
								]
							),
							'end_datetime' => new Fieldmanager_Datepicker(
								[
									'label' => __( 'End Date', 'cpr' ),
									'use_am_pm' => true,
									'use_time' => true,
								]
							),
							'location' => new Fieldmanager_TextField( __( 'Location', 'cpr' ) ),
						],
					]
				),
			],
		]
	);
	$fm->add_meta_box( __( 'Settings', 'cpr' ), [ 'event' ] );
}
add_action( 'fm_post_event', 'cpr_fm_post_event_settings' );
/* end fm:post-event-settings */
