<?php
/**
 * Custom post type for Episodes
 *
 * @package Cpr
 */

/**
 * Class for the episode post type.
 */
class Cpr_Post_Type_Episode extends Cpr_Post_Type {

	/**
	 * Name of the custom post type.
	 *
	 * @var string
	 */
	public $name = 'episode';

	/**
	 * Creates the post type.
	 */
	public function create_post_type() {
		register_post_type(
			$this->name,
			[
				'labels' => [
					'name'                  => __( 'Episodes', 'cpr' ),
					'singular_name'         => __( 'Episode', 'cpr' ),
					'add_new'               => __( 'Add New Episode', 'cpr' ),
					'add_new_item'          => __( 'Add New Episode', 'cpr' ),
					'edit_item'             => __( 'Edit Episode', 'cpr' ),
					'new_item'              => __( 'New Episode', 'cpr' ),
					'view_item'             => __( 'View Episode', 'cpr' ),
					'view_items'            => __( 'View Episodes', 'cpr' ),
					'search_items'          => __( 'Search Episodes', 'cpr' ),
					'not_found'             => __( 'No episodes found', 'cpr' ),
					'not_found_in_trash'    => __( 'No episodes found in Trash', 'cpr' ),
					'parent_item_colon'     => __( 'Parent Episode:', 'cpr' ),
					'all_items'             => __( 'All Episodes', 'cpr' ),
					'archives'              => __( 'Episode Archives', 'cpr' ),
					'attributes'            => __( 'Episode Attributes', 'cpr' ),
					'insert_into_item'      => __( 'Insert into episode', 'cpr' ),
					'uploaded_to_this_item' => __( 'Uploaded to this episode', 'cpr' ),
					'filter_items_list'     => __( 'Filter episodes list', 'cpr' ),
					'items_list_navigation' => __( 'Episodes list navigation', 'cpr' ),
					'items_list'            => __( 'Episodes list', 'cpr' ),
					'menu_name'             => __( 'Episodes', 'cpr' ),
				],
				'public' => true,
				'show_in_rest' => true,
				'menu_icon' => 'dashicons-format-audio',
				'supports' => [ 'title', 'thumbnail', 'author', 'editor', 'revisions', 'excerpt', 'custom-fields' ],
				'taxonomies' => [ 'category', 'post_tag', 'podcast', 'section' ],
			]
		);
	}
}
$cpr_post_type_episode = new Cpr_Post_Type_Episode();
