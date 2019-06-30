<?php
/**
 * Custom post type for External Links
 *
 * @package Cpr
 */

/**
 * Class for the external-link post type.
 */
class Cpr_Post_Type_External_Link extends Cpr_Post_Type {

	/**
	 * Name of the custom post type.
	 *
	 * @var string
	 */
	public $name = 'external-link';

	/**
	 * Creates the post type.
	 */
	public function create_post_type() {
		register_post_type(
			$this->name,
			[
				'labels' => [
					'name'                     => __( 'External Links', 'cpr' ),
					'singular_name'            => __( 'External Link', 'cpr' ),
					'add_new'                  => __( 'Add New External Link', 'cpr' ),
					'add_new_item'             => __( 'Add New External Link', 'cpr' ),
					'edit_item'                => __( 'Edit External Link', 'cpr' ),
					'new_item'                 => __( 'New External Link', 'cpr' ),
					'view_item'                => __( 'View External Link', 'cpr' ),
					'view_items'               => __( 'View External Links', 'cpr' ),
					'search_items'             => __( 'Search External Links', 'cpr' ),
					'not_found'                => __( 'No external links found', 'cpr' ),
					'not_found_in_trash'       => __( 'No external links found in Trash', 'cpr' ),
					'parent_item_colon'        => __( 'Parent External Link:', 'cpr' ),
					'all_items'                => __( 'All External Links', 'cpr' ),
					'archives'                 => __( 'External Link Archives', 'cpr' ),
					'attributes'               => __( 'External Link Attributes', 'cpr' ),
					'insert_into_item'         => __( 'Insert into external link', 'cpr' ),
					'uploaded_to_this_item'    => __( 'Uploaded to this external link', 'cpr' ),
					'featured_image'           => __( 'Featured Image', 'cpr' ),
					'set_featured_image'       => __( 'Set featured image', 'cpr' ),
					'remove_featured_image'    => __( 'Remove featured image', 'cpr' ),
					'use_featured_image'       => __( 'Use as featured image', 'cpr' ),
					'filter_items_list'        => __( 'Filter external links list', 'cpr' ),
					'items_list_navigation'    => __( 'External Links list navigation', 'cpr' ),
					'items_list'               => __( 'External Links list', 'cpr' ),
					'item_published'           => __( 'External Link published.', 'cpr' ),
					'item_published_privately' => __( 'External Link published privately.', 'cpr' ),
					'item_reverted_to_draft'   => __( 'External Link reverted to draft.', 'cpr' ),
					'item_scheduled'           => __( 'External Link scheduled.', 'cpr' ),
					'item_updated'             => __( 'External Link updated.', 'cpr' ),
					'menu_name'                => __( 'External Links', 'cpr' ),
				],
				'public' => true,
				'publicly_queryable' => false,
				'has_single' => false,
				'show_in_rest' => true,
				'menu_icon' => 'dashicons-admin-links',
				'supports' => [ 'title', 'editor', 'thumbnail', 'author', 'custom-fields' ],
			]
		);
	}
}
$cpr_post_type_external_link = new Cpr_Post_Type_External_Link();
