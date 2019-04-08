<?php
/**
 * Custom post type for Underwriters
 *
 * @package Cpr
 */

/**
 * Class for the underwriter post type.
 */
class Cpr_Post_Type_Underwriter extends Cpr_Post_Type {

	/**
	 * Name of the custom post type.
	 *
	 * @var string
	 */
	public $name = 'underwriter';

	/**
	 * Creates the post type.
	 */
	public function create_post_type() {
		register_post_type(
			$this->name,
			[
				'labels' => [
					'name'                     => __( 'Underwriters', 'cpr' ),
					'singular_name'            => __( 'Underwriter', 'cpr' ),
					'add_new'                  => __( 'Add New Underwriter', 'cpr' ),
					'add_new_item'             => __( 'Add New Underwriter', 'cpr' ),
					'edit_item'                => __( 'Edit Underwriter', 'cpr' ),
					'new_item'                 => __( 'New Underwriter', 'cpr' ),
					'view_item'                => __( 'View Underwriter', 'cpr' ),
					'view_items'               => __( 'View Underwriters', 'cpr' ),
					'search_items'             => __( 'Search Underwriters', 'cpr' ),
					'not_found'                => __( 'No underwriters found', 'cpr' ),
					'not_found_in_trash'       => __( 'No underwriters found in Trash', 'cpr' ),
					'parent_item_colon'        => __( 'Parent Underwriter:', 'cpr' ),
					'all_items'                => __( 'All Underwriters', 'cpr' ),
					'archives'                 => __( 'Underwriter Archives', 'cpr' ),
					'attributes'               => __( 'Underwriter Attributes', 'cpr' ),
					'insert_into_item'         => __( 'Insert into underwriter', 'cpr' ),
					'uploaded_to_this_item'    => __( 'Uploaded to this underwriter', 'cpr' ),
					'featured_image'           => __( 'Featured Image', 'cpr' ),
					'set_featured_image'       => __( 'Set featured image', 'cpr' ),
					'remove_featured_image'    => __( 'Remove featured image', 'cpr' ),
					'use_featured_image'       => __( 'Use as featured image', 'cpr' ),
					'filter_items_list'        => __( 'Filter underwriters list', 'cpr' ),
					'items_list_navigation'    => __( 'Underwriters list navigation', 'cpr' ),
					'items_list'               => __( 'Underwriters list', 'cpr' ),
					'item_published'           => __( 'Underwriter published.', 'cpr' ),
					'item_published_privately' => __( 'Underwriter published privately.', 'cpr' ),
					'item_reverted_to_draft'   => __( 'Underwriter reverted to draft.', 'cpr' ),
					'item_scheduled'           => __( 'Underwriter scheduled.', 'cpr' ),
					'item_updated'             => __( 'Underwriter updated.', 'cpr' ),
					'menu_name'                => __( 'Underwriters', 'cpr' ),
				],
				'public' => true,
				'publicly_queryable' => false,
				'has_archive' => true,
				'has_single' => false,
				'show_in_rest' => true,
				'menu_icon' => 'dashicons-money',
				'supports' => [ 'title', 'editor', 'thumbnail' ],
			]
		);
	}
}
$cpr_post_type_underwriter = new Cpr_Post_Type_Underwriter();
