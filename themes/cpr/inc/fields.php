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
	$fm->add_meta_box( __( 'Section', 'cpr' ), [ 'post', 'podcast-post' ], 'side' );
}
add_action( 'fm_post_post', 'cpr_fm_post_podcast_post_section' );
add_action( 'fm_post_podcast-post', 'cpr_fm_post_podcast_post_section' );
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



/* begin fm:post-post-settings */
/**
 * `post-post-settings` Fieldmanager fields.
 */
function cpr_fm_post_post_settings() {
	$fm = new Fieldmanager_Group(
		[
			'name' => 'post-post-settings',
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
							'primary_category_id' => new Fieldmanager_Select(
								[
									'label' => __( 'Primary Category', 'cpr' ),
									'description' => __( 'Begin typing to select a primary category.', 'cpr' ),
									'datasource' => new Fieldmanager_Datasource_Term(
										[
											'taxonomy' => 'category',
											'taxonomy_save_to_terms' => false,
											'only_save_to_taxonomy' => false,
										]
									),
								]
							),
						],
					]
				),
				'featured_media' => new Fieldmanager_Group(
					[
						'label' => __( 'Featured Media', 'cpr' ),
						'serialize_data' => false,
						'add_to_prefix' => false,
						'children' => [
							'disable_image' => new Fieldmanager_Checkbox( __( 'Hide Featured Image', 'cpr' ) ),
							'youtube_url' => new Fieldmanager_Link(
								[
									'label' => __( 'YouTube URL', 'cpr' ),
									'description' => __( 'Display this video at the top of the page.', 'cpr' ),
								]
							),
						],
					]
				),
				'social_and_seo' => new Fieldmanager_Group(
					[
						'label' => __( 'SEO and Social', 'cpr' ),
						'serialize_data' => false,
						'add_to_prefix' => false,
						'children' => [
							'seo' => new Fieldmanager_Group(
								[
									'label' => __( 'SEO Settings', 'cpr' ),
									'serialize_data' => false,
									'add_to_prefix' => false,
									'collapsed' => true,
									'children' => [
										'_meta_title' => new Fieldmanager_TextField( __( 'Title Tag', 'cpr' ) ),
										'_meta_description' => new Fieldmanager_TextArea(
											[
												'label' => __( 'Meta Description', 'cpr' ),
												'attributes' => [
													'style' => 'width: 100%;',
													'rows' => 5,
												],
											]
										),
										'_meta_keywords' => new Fieldmanager_TextArea(
											[
												'label' => __( 'Meta Keywords', 'cpr' ),
												'attributes' => [
													'style' => 'width: 100%;',
													'rows' => 5,
												],
											]
										),
									],
								]
							),
							'social' => new Fieldmanager_Group(
								[
									'label' => __( 'Social Media Settings', 'cpr' ),
									'serialize_data' => false,
									'add_to_prefix' => false,
									'collapsed' => true,
									'children' => [
										'social_title' => new Fieldmanager_TextField( __( 'Social Title', 'cpr' ) ),
										'social_description' => new Fieldmanager_TextArea(
											[
												'label' => __( 'Social Description', 'cpr' ),
												'attributes' => [
													'style' => 'width: 100%;',
													'rows' => 5,
												],
											]
										),
										'social_image_id' => new Fieldmanager_Media( __( 'Social Image', 'cpr' ) ),
									],
								]
							),
							'advanced_settings' => new Fieldmanager_Group(
								[
									'label' => __( 'Advanced Settings', 'cpr' ),
									'serialize_data' => false,
									'add_to_prefix' => false,
									'collapsed' => true,
									'children' => [
										'canonical_url' => new Fieldmanager_TextField(
											[
												'label' => __( 'Canonical Url', 'cpr' ),
												'description' => __( 'This is the original URL of syndicated content.', 'cpr' ),
											]
										),
										'de_index_google' => new Fieldmanager_Checkbox(
											[
												'label' => __( 'De-index in search engines', 'cpr' ),
												'description' => __( 'This will prevent search engines from indexing this content.', 'cpr' ),
											]
										),
									],
								]
							),
						],
					]
				),
			],
		]
	);
	$fm->add_meta_box( __( 'Article Settings', 'cpr' ), [ 'post' ] );
}
add_action( 'fm_post_post', 'cpr_fm_post_post_settings' );
/* end fm:post-post-settings */
