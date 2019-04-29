<?php
/**
 * Custom post type for Jobs
 *
 * @package Cpr
 */

/**
 * Class for the job post type.
 */
class Cpr_Post_Type_Job extends Cpr_Post_Type {

	/**
	 * Name of the custom post type.
	 *
	 * @var string
	 */
	public $name = 'job';

	/**
	 * Creates the post type.
	 */
	public function create_post_type() {
		register_post_type(
			$this->name,
			[
				'labels' => [
					'name'                     => __( 'Jobs', 'cpr' ),
					'singular_name'            => __( 'Job', 'cpr' ),
					'add_new'                  => __( 'Add New Job', 'cpr' ),
					'add_new_item'             => __( 'Add New Job', 'cpr' ),
					'edit_item'                => __( 'Edit Job', 'cpr' ),
					'new_item'                 => __( 'New Job', 'cpr' ),
					'view_item'                => __( 'View Job', 'cpr' ),
					'view_items'               => __( 'View Jobs', 'cpr' ),
					'search_items'             => __( 'Search Jobs', 'cpr' ),
					'not_found'                => __( 'No jobs found', 'cpr' ),
					'not_found_in_trash'       => __( 'No jobs found in Trash', 'cpr' ),
					'parent_item_colon'        => __( 'Parent Job:', 'cpr' ),
					'all_items'                => __( 'All Jobs', 'cpr' ),
					'archives'                 => __( 'Job Archives', 'cpr' ),
					'attributes'               => __( 'Job Attributes', 'cpr' ),
					'insert_into_item'         => __( 'Insert into job', 'cpr' ),
					'uploaded_to_this_item'    => __( 'Uploaded to this job', 'cpr' ),
					'featured_image'           => __( 'Featured Image', 'cpr' ),
					'set_featured_image'       => __( 'Set featured image', 'cpr' ),
					'remove_featured_image'    => __( 'Remove featured image', 'cpr' ),
					'use_featured_image'       => __( 'Use as featured image', 'cpr' ),
					'filter_items_list'        => __( 'Filter jobs list', 'cpr' ),
					'items_list_navigation'    => __( 'Jobs list navigation', 'cpr' ),
					'items_list'               => __( 'Jobs list', 'cpr' ),
					'item_published'           => __( 'Job published.', 'cpr' ),
					'item_published_privately' => __( 'Job published privately.', 'cpr' ),
					'item_reverted_to_draft'   => __( 'Job reverted to draft.', 'cpr' ),
					'item_scheduled'           => __( 'Job scheduled.', 'cpr' ),
					'item_updated'             => __( 'Job updated.', 'cpr' ),
					'menu_name'                => __( 'Jobs', 'cpr' ),
				],
				'public' => true,
				'has_archive' => true,
				'show_in_rest' => true,
				'menu_icon' => 'dashicons-portfolio',
				'supports' => [ 'title', 'editor', 'revisions' ],
			]
		);
	}
}
$cpr_post_type_job = new Cpr_Post_Type_Job();
