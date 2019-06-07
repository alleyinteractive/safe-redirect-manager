<?php
/**
 * Custom post type for Newsletter Posts
 *
 * @package Cpr
 */

/**
 * Class for the newsletter-post post type.
 */
class Cpr_Post_Type_Newsletter_Post extends Cpr_Post_Type {

	/**
	 * Name of the custom post type.
	 *
	 * @var string
	 */
	public $name = 'newsletter-post';

	/**
	 * Creates the post type.
	 */
	public function create_post_type() {
		register_post_type(
			$this->name,
			[
				'labels' => [
					'name'                     => __( 'Newsletter Posts', 'cpr' ),
					'singular_name'            => __( 'Newsletter Post', 'cpr' ),
					'add_new'                  => __( 'Add New Newsletter Post', 'cpr' ),
					'add_new_item'             => __( 'Add New Newsletter Post', 'cpr' ),
					'edit_item'                => __( 'Edit Newsletter Post', 'cpr' ),
					'new_item'                 => __( 'New Newsletter Post', 'cpr' ),
					'view_item'                => __( 'View Newsletter Post', 'cpr' ),
					'view_items'               => __( 'View Newsletter Posts', 'cpr' ),
					'search_items'             => __( 'Search Newsletter Posts', 'cpr' ),
					'not_found'                => __( 'No newsletter posts found', 'cpr' ),
					'not_found_in_trash'       => __( 'No newsletter posts found in Trash', 'cpr' ),
					'parent_item_colon'        => __( 'Parent Newsletter Post:', 'cpr' ),
					'all_items'                => __( 'All Newsletter Posts', 'cpr' ),
					'archives'                 => __( 'Newsletter Post Archives', 'cpr' ),
					'attributes'               => __( 'Newsletter Post Attributes', 'cpr' ),
					'insert_into_item'         => __( 'Insert into newsletter post', 'cpr' ),
					'uploaded_to_this_item'    => __( 'Uploaded to this newsletter post', 'cpr' ),
					'featured_image'           => __( 'Featured Image', 'cpr' ),
					'set_featured_image'       => __( 'Set featured image', 'cpr' ),
					'remove_featured_image'    => __( 'Remove featured image', 'cpr' ),
					'use_featured_image'       => __( 'Use as featured image', 'cpr' ),
					'filter_items_list'        => __( 'Filter newsletter posts list', 'cpr' ),
					'items_list_navigation'    => __( 'Newsletter Posts list navigation', 'cpr' ),
					'items_list'               => __( 'Newsletter Posts list', 'cpr' ),
					'item_published'           => __( 'Newsletter Post published.', 'cpr' ),
					'item_published_privately' => __( 'Newsletter Post published privately.', 'cpr' ),
					'item_reverted_to_draft'   => __( 'Newsletter Post reverted to draft.', 'cpr' ),
					'item_scheduled'           => __( 'Newsletter Post scheduled.', 'cpr' ),
					'item_updated'             => __( 'Newsletter Post updated.', 'cpr' ),
					'menu_name'                => __( 'Newsletter Posts', 'cpr' ),
				],
				'public' => true,
				'show_in_rest' => true,
				'show_in_menu' => false,
				'supports' => [ 'title', 'thumbnail', 'editor', 'revisions' ],
			]
		);
	}
}
$cpr_post_type_newsletter_post = new Cpr_Post_Type_Newsletter_Post();
