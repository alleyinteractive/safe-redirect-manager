<?php
/**
 * Custom post type for Podcast Episodes
 *
 * @package Cpr
 */

/**
 * Class for the podcast-episode post type.
 */
class Cpr_Post_Type_Podcast_Episode extends Cpr_Post_Type {

	/**
	 * Name of the custom post type.
	 *
	 * @var string
	 */
	public $name = 'podcast-episode';

	/**
	 * Creates the post type.
	 */
	public function create_post_type() {
		register_post_type(
			$this->name,
			[
				'labels' => [
					'name'                     => __( 'Podcast Episodes', 'cpr' ),
					'singular_name'            => __( 'Podcast Episode', 'cpr' ),
					'add_new'                  => __( 'Add New Podcast Episode', 'cpr' ),
					'add_new_item'             => __( 'Add New Podcast Episode', 'cpr' ),
					'edit_item'                => __( 'Edit Podcast Episode', 'cpr' ),
					'new_item'                 => __( 'New Podcast Episode', 'cpr' ),
					'view_item'                => __( 'View Podcast Episode', 'cpr' ),
					'view_items'               => __( 'View Podcast Episodes', 'cpr' ),
					'search_items'             => __( 'Search Podcast Episodes', 'cpr' ),
					'not_found'                => __( 'No podcast episodes found', 'cpr' ),
					'not_found_in_trash'       => __( 'No podcast episodes found in Trash', 'cpr' ),
					'parent_item_colon'        => __( 'Parent Podcast Episode:', 'cpr' ),
					'all_items'                => __( 'All Podcast Episodes', 'cpr' ),
					'archives'                 => __( 'Podcast Episode Archives', 'cpr' ),
					'attributes'               => __( 'Podcast Episode Attributes', 'cpr' ),
					'insert_into_item'         => __( 'Insert into podcast episode', 'cpr' ),
					'uploaded_to_this_item'    => __( 'Uploaded to this podcast episode', 'cpr' ),
					'featured_image'           => __( 'Featured Image', 'cpr' ),
					'set_featured_image'       => __( 'Set featured image', 'cpr' ),
					'remove_featured_image'    => __( 'Remove featured image', 'cpr' ),
					'use_featured_image'       => __( 'Use as featured image', 'cpr' ),
					'filter_items_list'        => __( 'Filter podcast episodes list', 'cpr' ),
					'items_list_navigation'    => __( 'Podcast Episodes list navigation', 'cpr' ),
					'items_list'               => __( 'Podcast Episodes list', 'cpr' ),
					'item_published'           => __( 'Podcast Episode published.', 'cpr' ),
					'item_published_privately' => __( 'Podcast Episode published privately.', 'cpr' ),
					'item_reverted_to_draft'   => __( 'Podcast Episode reverted to draft.', 'cpr' ),
					'item_scheduled'           => __( 'Podcast Episode scheduled.', 'cpr' ),
					'item_updated'             => __( 'Podcast Episode updated.', 'cpr' ),
					'menu_name'                => __( 'Podcast Episodes', 'cpr' ),
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
$cpr_post_type_podcast_episode = new Cpr_Post_Type_Podcast_Episode();
