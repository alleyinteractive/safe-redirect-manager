<?php
/**
 * Custom post type for Top 30s
 *
 * @package Cpr
 */

/**
 * Class for the top-30 post type.
 */
class Cpr_Post_Type_Top_30 extends Cpr_Post_Type {

	/**
	 * Name of the custom post type.
	 *
	 * @var string
	 */
	public $name = 'top-30';

	/**
	 * Creates the post type.
	 */
	public function create_post_type() {
		register_post_type(
			$this->name,
			[
				'labels' => [
					'name'                     => __( 'Top 30s', 'cpr' ),
					'singular_name'            => __( 'Top 30', 'cpr' ),
					'add_new'                  => __( 'Add New Top 30', 'cpr' ),
					'add_new_item'             => __( 'Add New Top 30', 'cpr' ),
					'edit_item'                => __( 'Edit Top 30', 'cpr' ),
					'new_item'                 => __( 'New Top 30', 'cpr' ),
					'view_item'                => __( 'View Top 30', 'cpr' ),
					'view_items'               => __( 'View Top 30s', 'cpr' ),
					'search_items'             => __( 'Search Top 30s', 'cpr' ),
					'not_found'                => __( 'No top 30s found', 'cpr' ),
					'not_found_in_trash'       => __( 'No top 30s found in Trash', 'cpr' ),
					'parent_item_colon'        => __( 'Parent Top 30:', 'cpr' ),
					'all_items'                => __( 'All Top 30s', 'cpr' ),
					'archives'                 => __( 'Top 30 Archives', 'cpr' ),
					'attributes'               => __( 'Top 30 Attributes', 'cpr' ),
					'insert_into_item'         => __( 'Insert into top 30', 'cpr' ),
					'uploaded_to_this_item'    => __( 'Uploaded to this top 30', 'cpr' ),
					'featured_image'           => __( 'Featured Image', 'cpr' ),
					'set_featured_image'       => __( 'Set featured image', 'cpr' ),
					'remove_featured_image'    => __( 'Remove featured image', 'cpr' ),
					'use_featured_image'       => __( 'Use as featured image', 'cpr' ),
					'filter_items_list'        => __( 'Filter top 30s list', 'cpr' ),
					'items_list_navigation'    => __( 'Top 30s list navigation', 'cpr' ),
					'items_list'               => __( 'Top 30s list', 'cpr' ),
					'item_published'           => __( 'Top 30 published.', 'cpr' ),
					'item_published_privately' => __( 'Top 30 published privately.', 'cpr' ),
					'item_reverted_to_draft'   => __( 'Top 30 reverted to draft.', 'cpr' ),
					'item_scheduled'           => __( 'Top 30 scheduled.', 'cpr' ),
					'item_updated'             => __( 'Top 30 updated.', 'cpr' ),
					'menu_name'                => __( 'Top 30s', 'cpr' ),
				],
				'public' => true,
				'has_archive' => true,
				'show_in_rest' => true,
				'menu_icon' => 'dashicons-star-filled',
				'rewrite' => [
					'slug' => 'openair/top-30',
				],
				'supports' => [ 'title', 'custom-fields' ],
			]
		);
	}
}
$cpr_post_type_top_30 = new Cpr_Post_Type_Top_30();
