<?php
/**
 * Custom post type for Podcast Posts
 *
 * @package CPR
 */

/**
 * Class for the podcast-post post type.
 */
class Cpr_Post_Type_Podcast_Post extends Cpr_Post_Type {

	/**
	 * Name of the custom post type.
	 *
	 * @var string
	 */
	public $name = 'podcast-post';

	/**
	 * Creates the post type.
	 */
	public function create_post_type() {
		register_post_type(
			$this->name,
			[
				'labels' => [
					'name'                  => __( 'Podcast Posts', 'cpr' ),
					'singular_name'         => __( 'Podcast Post', 'cpr' ),
					'add_new'               => __( 'Add New Podcast', 'cpr' ),
					'add_new_item'          => __( 'Add New Podcast Post', 'cpr' ),
					'edit_item'             => __( 'Edit Podcast', 'cpr' ),
					'new_item'              => __( 'New Podcast Post', 'cpr' ),
					'view_item'             => __( 'View Podcast Post', 'cpr' ),
					'view_items'            => __( 'View Podcast Posts', 'cpr' ),
					'search_items'          => __( 'Search Podcast Posts', 'cpr' ),
					'not_found'             => __( 'No podcast posts found', 'cpr' ),
					'not_found_in_trash'    => __( 'No podcast posts found in Trash', 'cpr' ),
					'parent_item_colon'     => __( 'Parent Podcast Post:', 'cpr' ),
					'all_items'             => __( 'All Podcast Posts', 'cpr' ),
					'archives'              => __( 'Podcast Post Archives', 'cpr' ),
					'attributes'            => __( 'Podcast Post Attributes', 'cpr' ),
					'insert_into_item'      => __( 'Insert into podcast post', 'cpr' ),
					'uploaded_to_this_item' => __( 'Uploaded to this podcast post', 'cpr' ),
					'filter_items_list'     => __( 'Filter podcast posts list', 'cpr' ),
					'items_list_navigation' => __( 'Podcast Posts list navigation', 'cpr' ),
					'items_list'            => __( 'Podcast Posts list', 'cpr' ),
					'menu_name'             => __( 'Podcast Posts', 'cpr' ),
				],
				'public' => true,
				'show_in_nav_menus' => false,
				'show_in_menu' => false,
				'supports' => [ 'title', 'thumbnail', 'editor', 'revisions' ],
			]
		);
	}
}
$cpr_post_type_podcast_post = new Cpr_Post_Type_Podcast_Post();
