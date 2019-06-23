<?php
/**
 * Field helpers.
 *
 * @package CPR
 */

namespace CPR\Fields;

function get_settings() {
	return [
		'excerpt' => new \Alleypack\Fieldmanager\Fields\Fieldmanager_Excerpt( __( 'Excerpt', 'cpr' ) ),

	];
}

function get_taxonomy_fields() {
	return [
		'title' => new \Alleypack\Fieldmanager\Fields\Fieldmanager_Content(
			[
				'description' => '<h2 style="font-size: 24px; font-style: normal; padding: 0px;">Taxonomies</h2>',
				'attributes'  => [
					'style' => 'font-size: 42px;',
				],
			]
		),
		'section' => \Alleypack\Fieldmanager\Patterns\get_term_fields(
			[
				'label'           => __( 'Section', 'cpr' ),
				'taxonomy'        => 'section',
				'can_select_term' => true,
				'field_type'      => 'select',
			]
		),
		'category' => \Alleypack\Fieldmanager\Patterns\get_term_fields(
			[
				'label'            => __( 'Category', 'cpr' ),
				'field_type'       => 'checkboxes',
				'has_primary'      => true,
				'can_select_terms' => true,
				'field_type'       => 'checkboxes',
			]
		),
		'post_tags' => \Alleypack\Fieldmanager\Patterns\get_term_fields(
			[
				'label'            => __( 'Tag', 'cpr' ),
				'taxonomy'         => 'post_tag',
				'can_select_terms' => true,
				'field_type'       => 'autocomplete',
			]
		),
	];
}

function get_segment_fields() {
	return [
		'show_segment_ids' => new \Fieldmanager_Zone_Field(
			[
				'label' => __( 'Show Segments', 'cpr' ),
				'description' => __( 'Select the segments for this show.', 'cpr' ),
				'query_args' => [
					'post_type' => [ 'show-segment' ],
					'meta_query' => [ [
						'key' => '_show_episode_id',
						'compare' => 'NOT EXISTS',
					] ],
				],
			]
		),
	];
}

function get_featured_media_fields() {
	return [
		'_thumbnail_id' => new \Fieldmanager_Media(__( 'Select an image to be used as the article thumbnail on the homepage, term archives, search results, and other archives.', 'cpr' ) ),
		'disable_image' => new \Fieldmanager_Checkbox( __( 'Hide Featured Image', 'cpr' ) ),
		'youtube_url'   => new \Fieldmanager_Link(
			[
				'label'       => __( 'YouTube URL', 'cpr' ),
				'description' => __( 'Display this video at the top of the page.', 'cpr' ),
			]
		),
	];
}
