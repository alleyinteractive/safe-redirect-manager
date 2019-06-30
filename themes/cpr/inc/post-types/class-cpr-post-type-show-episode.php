<?php
/**
 * Custom post type for Show Episodes
 *
 * @package Cpr
 */

/**
 * Class for the show-episode post type.
 */
class Cpr_Post_Type_Show_Episode extends Cpr_Post_Type {

	/**
	 * Name of the custom post type.
	 *
	 * @var string
	 */
	public $name = 'show-episode';

	/**
	 * Creates the post type.
	 */
	public function create_post_type() {
		register_post_type(
			$this->name,
			[
				'labels' => [
					'name'                     => __( 'Show Episodes', 'cpr' ),
					'singular_name'            => __( 'Show Episode', 'cpr' ),
					'add_new'                  => __( 'Add New Show Episode', 'cpr' ),
					'add_new_item'             => __( 'Add New Show Episode', 'cpr' ),
					'edit_item'                => __( 'Edit Show Episode', 'cpr' ),
					'new_item'                 => __( 'New Show Episode', 'cpr' ),
					'view_item'                => __( 'View Show Episode', 'cpr' ),
					'view_items'               => __( 'View Show Episodes', 'cpr' ),
					'search_items'             => __( 'Search Show Episodes', 'cpr' ),
					'not_found'                => __( 'No show episodes found', 'cpr' ),
					'not_found_in_trash'       => __( 'No show episodes found in Trash', 'cpr' ),
					'parent_item_colon'        => __( 'Parent Show Episode:', 'cpr' ),
					'all_items'                => __( 'Show Episodes', 'cpr' ),
					'archives'                 => __( 'Show Episode Archives', 'cpr' ),
					'attributes'               => __( 'Show Episode Attributes', 'cpr' ),
					'insert_into_item'         => __( 'Insert into show episode', 'cpr' ),
					'uploaded_to_this_item'    => __( 'Uploaded to this show episode', 'cpr' ),
					'featured_image'           => __( 'Featured Image', 'cpr' ),
					'set_featured_image'       => __( 'Set featured image', 'cpr' ),
					'remove_featured_image'    => __( 'Remove featured image', 'cpr' ),
					'use_featured_image'       => __( 'Use as featured image', 'cpr' ),
					'filter_items_list'        => __( 'Filter show episodes list', 'cpr' ),
					'items_list_navigation'    => __( 'Show Episodes list navigation', 'cpr' ),
					'items_list'               => __( 'Show Episodes list', 'cpr' ),
					'item_published'           => __( 'Show Episode published.', 'cpr' ),
					'item_published_privately' => __( 'Show Episode published privately.', 'cpr' ),
					'item_reverted_to_draft'   => __( 'Show Episode reverted to draft.', 'cpr' ),
					'item_scheduled'           => __( 'Show Episode scheduled.', 'cpr' ),
					'item_updated'             => __( 'Show Episode updated.', 'cpr' ),
					'menu_name'                => __( 'Show Episodes', 'cpr' ),
				],
				'public' => true,
				'show_in_rest' => true,
				'show_in_menu' => false,
				'show_in_admin_bar' => true,
				'supports' => [ 'title', 'author', 'editor', 'revisions', 'custom-fields' ],
				'taxonomies' => [ 'category', 'post_tag', 'show', 'section' ],
			]
		);
	}
}
$cpr_post_type_show_episode = new Cpr_Post_Type_Show_Episode();
