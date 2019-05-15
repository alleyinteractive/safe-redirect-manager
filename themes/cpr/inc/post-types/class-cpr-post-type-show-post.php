<?php
/**
 * Custom post type for Show Posts
 *
 * @package Cpr
 */

/**
 * Class for the show-post post type.
 */
class Cpr_Post_Type_Show_Post extends Cpr_Post_Type {

	/**
	 * Name of the custom post type.
	 *
	 * @var string
	 */
	public $name = 'show-post';

	/**
	 * Creates the post type.
	 */
	public function create_post_type() {
		register_post_type(
			$this->name,
			[
				'labels' => [
					'name'                     => __( 'Show Posts', 'cpr' ),
					'singular_name'            => __( 'Show Post', 'cpr' ),
					'add_new'                  => __( 'Add New Show', 'cpr' ),
					'add_new_item'             => __( 'Add New Show Post', 'cpr' ),
					'edit_item'                => __( 'Edit Show', 'cpr' ),
					'new_item'                 => __( 'New Show Post', 'cpr' ),
					'view_item'                => __( 'View Show Post', 'cpr' ),
					'view_items'               => __( 'View Show Posts', 'cpr' ),
					'search_items'             => __( 'Search Show Posts', 'cpr' ),
					'not_found'                => __( 'No show posts found', 'cpr' ),
					'not_found_in_trash'       => __( 'No show posts found in Trash', 'cpr' ),
					'parent_item_colon'        => __( 'Parent Show Post:', 'cpr' ),
					'all_items'                => __( 'All Show Posts', 'cpr' ),
					'archives'                 => __( 'Show Post Archives', 'cpr' ),
					'attributes'               => __( 'Show Post Attributes', 'cpr' ),
					'insert_into_item'         => __( 'Insert into show post', 'cpr' ),
					'uploaded_to_this_item'    => __( 'Uploaded to this show post', 'cpr' ),
					'featured_image'           => __( 'Featured Image', 'cpr' ),
					'set_featured_image'       => __( 'Set featured image', 'cpr' ),
					'remove_featured_image'    => __( 'Remove featured image', 'cpr' ),
					'use_featured_image'       => __( 'Use as featured image', 'cpr' ),
					'filter_items_list'        => __( 'Filter show posts list', 'cpr' ),
					'items_list_navigation'    => __( 'Show Posts list navigation', 'cpr' ),
					'items_list'               => __( 'Show Posts list', 'cpr' ),
					'item_published'           => __( 'Show Post published.', 'cpr' ),
					'item_published_privately' => __( 'Show Post published privately.', 'cpr' ),
					'item_reverted_to_draft'   => __( 'Show Post reverted to draft.', 'cpr' ),
					'item_scheduled'           => __( 'Show Post scheduled.', 'cpr' ),
					'item_updated'             => __( 'Show Post updated.', 'cpr' ),
					'menu_name'                => __( 'Show Posts', 'cpr' ),
				],
				'show_ui' => true,
				'show_in_menu' => false,
				'supports' => [ 'title', 'thumbnail', 'editor', 'revisions' ],
				'taxonomies' => [ 'section' ],
			]
		);
	}
}
$cpr_post_type_show_post = new Cpr_Post_Type_Show_Post();
