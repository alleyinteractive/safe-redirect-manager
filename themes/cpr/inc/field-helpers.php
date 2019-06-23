<?php
/**
 * Field helpers.
 *
 * @package CPR
 */

namespace CPR\Fields;

/**
 * Get the current post type from the post edit screen.
 *
 * @return string
 */
function get_current_post_type() : string {

	// Get post type by the post iD.
	if ( isset( $_GET['post'] ) ) {
		return get_post_type( absint( $_GET['post'] ) );
	}

	// New post screen.
	if ( isset( $_GET['post_type'] ) ) {
		$post_type = sanitize_text_field( wp_unslash( $_GET['post_type'] ) );
		if ( post_type_exists( $post_type ) ) {
			return $post_type;
		}
	}

	return 'post';
}

/**
 * Get the post settings.
 *
 * @return array
 */
function get_settings() : array {
	return [
		'_group_title' => new \Alleypack\Fieldmanager\Fields\Fieldmanager_Content(
			[
				'description' => sprintf(
					'<h2 style="font-size: 24px; font-style: normal; padding: 0px;">%1$s</h2>',
					__( 'Settings', 'cpr' )
				),
			]
		),
		'excerpt' => new \Alleypack\Fieldmanager\Fields\Fieldmanager_Excerpt( __( 'Excerpt', 'cpr' ) ),
	];
}

/**
 * Get post taxonomy settings.
 *
 * @return array
 */
function get_taxonomy_fields() : array {
	$fields  = [
		'_group_title' => new \Alleypack\Fieldmanager\Fields\Fieldmanager_Content(
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
				'show_group'      => false,
				'field_type'      => 'select',
			]
		),
		'podcast' => \Alleypack\Fieldmanager\Patterns\get_term_fields(
			[
				'label'           => __( 'Podcast', 'cpr' ),
				'taxonomy'        => 'podcast',
				'can_select_term' => true,
				'show_group'      => false,
				'field_type'      => 'select',
			]
		),
		'show' => \Alleypack\Fieldmanager\Patterns\get_term_fields(
			[
				'label'           => __( 'Show', 'cpr' ),
				'taxonomy'        => 'show',
				'can_select_term' => true,
				'show_group'      => false,
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

	switch ( get_current_post_type() ) {
		case 'podcast-episode':
			unset( $fields['show'] );
			break;
		case 'show-episode':
			unset( $fields['podcast'] );
			break;
		case 'show-segment':
			unset( $fields['podcast'] );
			unset( $fields['show'] );
			break;
		case 'page':
			unset( $fields['podcast'] );
			unset( $fields['show'] );
			break;
		case 'post':
		default:
			unset( $fields['podcast'] );
			unset( $fields['show'] );
			break;
	}

	return $fields;
}

/**
 * Get segment fields.
 *
 * @return array
 */
function get_segment_fields() : array {
	return [
		'_group_title'     => new \Alleypack\Fieldmanager\Fields\Fieldmanager_Content(
			[
				'description' => sprintf(
					'<h2 style="font-size: 24px; font-style: normal; padding: 0px;">%1$s</h2>',
					__( 'Episode Segments', 'cpr' )
				),
			]
		),
		'show_segment_ids' => new \Fieldmanager_Zone_Field(
			[
				'description' => __( 'Select the segments for this show.', 'cpr' ),
				'query_args'  => [
					'post_type'  => [ 'show-segment' ],
					'meta_query' => [
						[
							'key'     => '_show_episode_id',
							'compare' => 'NOT EXISTS',
						],
					],
				],
			]
		),
	];
}
