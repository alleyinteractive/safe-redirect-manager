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
