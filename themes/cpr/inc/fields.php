<?php
/**
 * Fieldmanager fields
 *
 * @package CPR
 */



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
				'general' => new Fieldmanager_Group(
					[
						'label' => __( 'General settings', 'cpr' ),
						'children' => [
							'fallback_image_id' => new Fieldmanager_Media(
								[
									'label' => __( 'Fallback image', 'cpr' ),
									'description' => __( "This image will appear in place of a post's featured image when no other image has been set.", 'cpr' ),
								]
							),
						],
					]
				),
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
							'cta' => new Fieldmanager_Group(
								[
									'children' => ( new \CPR\Components\Donate\Donate_CTA() )->get_fm_fields(),
								]
							),
							'button' => new Fieldmanager_Group(
								[
									'children' => ( new \CPR\Components\Donate\Donate_Button() )->get_fm_fields(),
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
									'children' => ( new \CPR\Components\Modules\Newsletter() )->get_fm_fields(),
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
									'children' => ( new \CPR\Components\Social_Links() )->get_fm_fields(),
								]
							),
						],
					]
				),
				'careers' => new Fieldmanager_Group(
					[
						'label' => __( 'Careers', 'cpr' ),
						'children' => [
							'footer_content' => new Fieldmanager_RichTextArea( __( 'Job Listings Footer', 'cpr' ) ),
						],
					]
				),
				'gtm' => new Fieldmanager_Group(
					[
						'label' => __( 'Google Tag Manager Settings', 'cpr' ),
						'children' => [
							'container_id' => new Fieldmanager_TextField( __( 'Google Tag Manager Container ID', 'cpr' ) ),
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


/* begin fm:post-guest-author-settings */
/**
 * `post-guest-author-settings` Fieldmanager fields.
 */
function cpr_fm_post_guest_author_settings() {
	$fm = new Fieldmanager_Group(
		[
			'name' => 'post-guest-author-settings',
			'serialize_data' => false,
			'add_to_prefix' => false,
			'children' => [
				'cap-user_email' => new Fieldmanager_TextField( __( 'Email', 'cpr' ) ),
				'title' => new Fieldmanager_TextField( __( 'Title', 'cpr' ) ),
				'twitter' => new Fieldmanager_TextField(
					[
						'label' => __( 'Twitter', 'cpr' ),
						'sanitize' => function( $value ) { return str_replace( '@', '', $value ); },
					]
				),
				'short_bio' => new Fieldmanager_RichTextArea(
					[
						'label' => __( 'Short Bio', 'cpr' ),
						'description' => __( 'This will be displayed on articles and the author archive.', 'cpr' ),
						'buttons_1' => [ 'bold', 'italic', 'link' ],
						'buttons_2' => [],
						'sanitize' => 'wp_filter_post_kses',
						'editor_settings' => [
							'media_buttons' => false,
						],
						'attributes' => [
							'style' => 'width: 100%',
							'rows' => 4,
						],
					]
				),
				'description' => new Fieldmanager_RichTextArea(
					[
						'label' => __( 'Long Bio', 'cpr' ),
						'buttons_1' => [ 'bold', 'italic', 'link' ],
						'buttons_2' => [],
						'sanitize' => 'wp_filter_post_kses',
						'editor_settings' => [
							'media_buttons' => false,
						],
						'attributes' => [
							'style' => 'width: 100%',
							'rows' => 4,
						],
					]
				),
				'type' => new Fieldmanager_Select(
					[
						'label' => __( 'Type', 'cpr' ),
						'options' => [
							'author' => __( 'Author', 'cpr' ),
							'host' => __( 'Host', 'cpr' ),
						],
					]
				),
			],
		]
	);
	$fm->add_meta_box( __( 'Info', 'cpr' ), [ 'guest-author' ], 'normal' );
}
add_action( 'fm_post_guest-author', 'cpr_fm_post_guest_author_settings' );
/* end fm:post-guest-author-settings */

/* begin fm:post-top-30-albums */
/**
 * `album_ids` Fieldmanager fields.
 */
function cpr_fm_post_top_30_albums() {
	$fm = new Fieldmanager_Zone_Field(
		[
			'name' => 'album_ids',
			'description' => __( 'Select the top 30 albums for this week.', 'cpr' ),
			'description_after_element' => false,
			'post_limit' => 30,
			'query_args' => [
				'post_type' => [ 'album' ],
			],
		]
	);
	$fm->add_meta_box( __( 'Top 30 Albums', 'cpr' ), [ 'top-30' ], 'normal', 'high' );
}
add_action( 'fm_post_top-30', 'cpr_fm_post_top_30_albums' );
/* end fm:post-top-30-albums */

/* begin fm:post-album-settings */
/**
 * `post-album-settings` Fieldmanager fields.
 */
function cpr_fm_post_album_settings() {
	$fm = new Fieldmanager_Group(
		[
			'name' => 'post-album-settings',
			'serialize_data' => false,
			'add_to_prefix' => false,
			'tabbed' => 'vertical',
			'children' => [
				'album_details' => new Fieldmanager_Group(
					[
						'label' => __( 'Album Details', 'cpr' ),
						'serialize_data' => false,
						'add_to_prefix' => false,
						'children' => [
							'_thumbnail_id' => new Fieldmanager_Media( __( 'Album cover', 'cpr' ) ),
							'year' => new Fieldmanager_TextField( __( 'Year', 'cpr' ) ),
							'artist_id' => new Fieldmanager_Autocomplete(
								[
									'label' => __( 'Artist', 'cpr' ),
									'description' => __( 'Select an artist.', 'cpr' ),
									'remove_default_meta_boxes' => true,
									'datasource' => new Fieldmanager_Datasource_Term(
										[
											'taxonomy' => 'artist',
											'taxonomy_save_to_terms' => true,
											'only_save_to_taxonomy' => true,
										]
									),
								]
							),
							'label_id' => new Fieldmanager_Autocomplete(
								[
									'label' => __( 'Album label', 'cpr' ),
									'description' => __( 'Select a label.', 'cpr' ),
									'remove_default_meta_boxes' => true,
									'datasource' => new Fieldmanager_Datasource_Term(
										[
											'taxonomy' => 'label',
											'taxonomy_save_to_terms' => true,
											'only_save_to_taxonomy' => true,
										]
									),
								]
							),
						],
					]
				),
			],
		]
	);
	$fm->add_meta_box( __( 'Settings', 'cpr' ), [ 'album' ] );
}
add_action( 'fm_post_album', 'cpr_fm_post_album_settings' );
/* end fm:post-album-settings */

/* begin fm:post-underwriter-settings */
/**
 * `post-underwriter-settings` Fieldmanager fields.
 */
function cpr_fm_post_underwriter_settings() {
	$fm = new Fieldmanager_Group(
		[
			'name' => 'post-underwriter-settings',
			'serialize_data' => false,
			'add_to_prefix' => false,
			'children' => [
				'description' => new Fieldmanager_RichTextArea(
					[
						'label' => __( 'Description', 'cpr' ),
						'buttons_1' => [ 'bold', 'italic', 'link' ],
						'buttons_2' => [],
						'sanitize' => 'wp_filter_post_kses',
						'editor_settings' => [
							'media_buttons' => false,
						],
						'attributes' => [
							'style' => 'width: 100%',
							'rows' => 4,
						],
					]
				),
				'link' => new Fieldmanager_Link( __( 'Website', 'cpr' ) ),
				'address' => new Fieldmanager_TextArea( __( 'Address', 'cpr' ) ),
				'phone_number' => new Fieldmanager_Textfield( __( 'Phone Number', 'cpr' ) ),
				'is_corporate_partner' => new Fieldmanager_Checkbox( __( 'Corporate Partner', 'cpr' ) ),
				'is_enhanced_listing' => new Fieldmanager_Checkbox(
					[
						'label' => __( 'Enhanced Listing', 'cpr' ),
						'description' => __( 'This underwriter will always be toggled open.', 'cpr' ),
					]
				),
			],
		]
	);
	$fm->add_meta_box( __( 'Settings', 'cpr' ), [ 'underwriter' ] );
}
add_action( 'fm_post_underwriter', 'cpr_fm_post_underwriter_settings' );
/* end fm:post-underwriter-settings */

/* begin fm:post-external-link-settings */
/**
 * `post-external-link-settings` Fieldmanager fields.
 */
function cpr_fm_post_external_link_settings() {
	$fm = new Fieldmanager_Group(
		[
			'name' => 'post-external-link-settings',
			'serialize_data' => false,
			'add_to_prefix' => false,
			'children' => [
				'link' => new Fieldmanager_Link( __( 'Link to External Content', 'cpr' ) ),
				'eyebrow_label' => new Fieldmanager_TextField( __( 'Eyebrow Label', 'cpr' ) ),
				'eyebrow_link' => new Fieldmanager_Link( __( 'Eyebrow Link', 'cpr' ) ),
			],
		]
	);
	$fm->add_meta_box( __( 'Settings', 'cpr' ), [ 'external-link' ] );
}
add_action( 'fm_post_external-link', 'cpr_fm_post_external_link_settings' );
/* end fm:post-external-link-settings */

/* begin fm:post-alert-settings */
/**
 * `post-alert-settings` Fieldmanager fields.
 */
function cpr_fm_post_alert_settings() {
	$fm = new Fieldmanager_Group(
		[
			'name' => 'post-alert-settings',
			'serialize_data' => false,
			'add_to_prefix' => false,
			'children' => [
				'link' => new Fieldmanager_Link( __( 'Link', 'cpr' ) ),
				'section' => new Fieldmanager_Checkboxes(
					[
						'label' => __( 'Section', 'cpr' ),
						'datasource' => new Fieldmanager_Datasource_Term(
							[
								'taxonomy' => 'section',
								'taxonomy_args' => [
									'hide_empty' => false,
								],
							]
						),
					]
				),
				'text' => new Fieldmanager_RichTextArea( __( 'Alert Text', 'cpr' ) ),
				'eyebrow' => new Fieldmanager_TextField( __( 'Eyebrow', 'cpr' ) ),
				'start_date' => new Fieldmanager_Datepicker( __( 'Start Date', 'cpr' ) ),
			],
		]
	);
	$fm->add_meta_box( __( 'Settings', 'cpr' ), [ 'alert' ] );
}
add_action( 'fm_post_alert', 'cpr_fm_post_alert_settings' );
/* end fm:post-alert-settings */

/* begin fm:post-show-episode-segments */
/**
 * `post-show-episode-segments` Fieldmanager fields.
 */
function cpr_fm_post_show_episode_segments() {
	$fm = new Fieldmanager_Group(
		[
			'name' => 'post-show-episode-segments',
			'serialize_data' => false,
			'add_to_prefix' => false,
			'children' => [
				'show_segment_ids' => new Fieldmanager_Zone_Field(
					[
						'label' => __( 'Show Segments', 'cpr' ),
						'description' => __( 'Select the segments for this show.', 'cpr' ),
						'query_args' => [
							'post_type' => [ 'show-segment' ],
							'meta_query' => [
								[
									'key' => '_show_episode_id',
									'compare' => 'NOT EXISTS',
								],
							],
						],
					]
				),
			],
		]
	);
	$fm->add_meta_box( __( 'Settings', 'cpr' ), [ 'show-episode' ], 'normal', 'high' );
}
add_action( 'fm_post_show-episode', 'cpr_fm_post_show_episode_segments' );
/* end fm:post-show-episode-segments */

/* begin fm:post-mixed-featured-audio */
/**
 * `audio_id` Fieldmanager fields.
 */
function cpr_fm_post_mixed_featured_audio() {
	$fm = new Fieldmanager_Media(
		[
			'name' => 'audio_id',
		]
	);
	$fm->add_meta_box( __( 'Featured Audio', 'cpr' ), [ 'podcast-episode', 'show-segment' ], 'normal', 'high' );
}
add_action( 'fm_post_podcast-episode', 'cpr_fm_post_mixed_featured_audio' );
add_action( 'fm_post_show-segment', 'cpr_fm_post_mixed_featured_audio' );
/* end fm:post-mixed-featured-audio */


/* begin fm:post-podcast-and-show-settings */
/**
 * `post-podcast-and-show-settings` Fieldmanager fields.
 */
function cpr_fm_post_podcast_and_show_settings() {
	$fm = new Fieldmanager_Group(
		[
			'name' => 'post-podcast-and-show-settings',
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
							'teaser' => new Fieldmanager_TextArea( __( 'Podcast/Show Teaser', 'cpr' ) ),
							'description' => new Fieldmanager_RichTextArea(
								[
									'label' => __( 'Podcast/Show Description', 'cpr' ),
									'buttons_1' => [ 'bold', 'italic', 'link' ],
									'buttons_2' => [],
									'sanitize' => 'wp_filter_post_kses',
									'editor_settings' => [
										'media_buttons' => false,
									],
									'attributes' => [
										'style' => 'width: 100%',
										'rows' => 4,
									],
								]
							),
							'times' => new Fieldmanager_TextField( __( 'Podcast/Show Times', 'cpr' ) ),
						],
					]
				),
				'subscribe' => new Fieldmanager_Group(
					[
						'label' => __( 'Subscribe', 'cpr' ),
						'children' => [
							'buttons' => new Fieldmanager_Group(
								[
									'label' => __( 'Subscribe Buttons', 'cpr' ),
									'limit' => 0,
									'add_more_label' => __( 'Add Button', 'cpr' ),
									'extra_elements' => 0,
									'sortable' => true,
									'children' => [
										'label' => new Fieldmanager_TextField( __( 'Label', 'cpr' ) ),
										'link' => new Fieldmanager_Link( __( 'Link', 'cpr' ) ),
									],
								]
							),
						],
					]
				),
				'highlighted_episodes' => new Fieldmanager_Group(
					[
						'label' => __( 'Highlighted Episodes', 'cpr' ),
						'children' => [
							'content_item_ids' => new Fieldmanager_Zone_Field(
								[
									'label' => __( 'Highlighted Episodes', 'cpr' ),
									'post_limit' => 4,
									'query_args' => \CPR\Components\Templates\Podcast_And_Show::get_highlighted_episodes_query_args(),
								]
							),
						],
					]
				),
				'hosts' => new Fieldmanager_Group(
					[
						'label' => __( 'Hosts', 'cpr' ),
						'children' => \CPR\Components\Modules\Grid_Group::get_fm_fields(),
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
						],
					]
				),
			],
		]
	);
	$fm->add_meta_box( __( 'Settings', 'cpr' ), [ 'podcast-post', 'show-post' ] );
}
add_action( 'fm_post_podcast-post', 'cpr_fm_post_podcast_and_show_settings' );
add_action( 'fm_post_show-post', 'cpr_fm_post_podcast_and_show_settings' );
/* end fm:post-podcast-and-show-settings */

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
							'excerpt' => new \Alleypack\Fieldmanager\Fields\Fieldmanager_Excerpt( __( 'Excerpt', 'cpr' ) ),
							'primary_category_id' => new Fieldmanager_Select(
								[
									'label' => __( 'Primary Category', 'cpr' ),
									'description' => __( 'Select a primary category to be used as the eyebrow site-wide.', 'cpr' ),
									'datasource' => new Fieldmanager_Datasource_Term(
										[
											'taxonomy' => 'category',
											'taxonomy_save_to_terms' => false,
											'only_save_to_taxonomy' => false,
										]
									),
								]
							),
							'keep_reading_ids' => new Fieldmanager_Zone_Field(
								[
									'label' => __( 'Keep Reading', 'cpr' ),
									'query_args' => [
										'post_type' => \CPR\get_content_post_types(),
									],
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
							'_thumbnail_id' => new Fieldmanager_Media( __( 'Select an image to be used as the article thumbnail on the homepage, term archives, search results, and other archives.', 'cpr' ) ),
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
	$fm->add_meta_box( __( 'Settings', 'cpr' ), [ 'post' ] );
}
add_action( 'fm_post_post', 'cpr_fm_post_post_settings' );
/* end fm:post-post-settings */

/* begin fm:post-tribe_events_section */
/**
 * `section_id` Fieldmanager fields.
 */
function cpr_fm_post_tribe_events_section() {
	$fm = new Fieldmanager_Select(
		[
			'name' => 'section_id',
			'description' => __( 'Select a section.', 'cpr' ),
			'datasource' => new Fieldmanager_Datasource_Term(
				[
					'taxonomy' => 'section',
					'taxonomy_save_to_terms' => true,
					'only_save_to_taxonomy' => true,
				]
			),
		]
	);
	$fm->add_meta_box( __( 'Section', 'cpr' ), [ 'tribe_events' ], 'normal', 'high' );
}
add_action( 'fm_post_tribe_events', 'cpr_fm_post_tribe_events_section' );
/* end fm:post-tribe_events_section */

/* begin fm:post-page-settings */
/**
 * `post-page-settings` Fieldmanager fields.
 */
function cpr_fm_post_page_settings() {
	$fm = new Fieldmanager_Group(
		[
			'name' => 'post-page-settings',
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
	$fm->add_meta_box( __( 'Settings', 'cpr' ), [ 'page' ] );
}
add_action( 'fm_post_page', 'cpr_fm_post_page_settings' );
/* end fm:post-page-settings */

/* begin fm:post-newsletter-settings */
/**
 * `post-newsletter-settings` Fieldmanager fields.
 */
function cpr_fm_post_newsletter_settings() {
	$fm = new Fieldmanager_Group(
		[
			'name' => 'post-newsletter-settings',
			'serialize_data' => false,
			'add_to_prefix' => false,
			'children' => [
				'newsletter_html' => new Fieldmanager_TextArea(
					[
						'label' => __( 'Newsletter HTML', 'cpr' ),
						'attributes' => [
							'rows' => 40,
							'style' => 'width: 100%',
						],
					]
				),
			],
		]
	);
	$fm->add_meta_box( __( 'Settings', 'cpr' ), [ 'newsletter-single' ], 'normal', 'high' );
}
add_action( 'fm_post_newsletter-single', 'cpr_fm_post_newsletter_settings' );
/* end fm:post-newsletter-settings */
