<?php
/**
 * Custom post type for Show Segments
 *
 * @package Cpr
 */

/**
 * Class for the show-segment post type.
 */
class Cpr_Post_Type_Show_Segment extends Cpr_Post_Type {

	/**
	 * Name of the custom post type.
	 *
	 * @var string
	 */
	public $name = 'show-segment';

	/**
	 * Creates the post type.
	 */
	public function create_post_type() {
		register_post_type(
			$this->name,
			[
				'labels' => [
					'name'                     => __( 'Show Segments', 'cpr' ),
					'singular_name'            => __( 'Show Segment', 'cpr' ),
					'add_new'                  => __( 'Add New Show Segment', 'cpr' ),
					'add_new_item'             => __( 'Add New Show Segment', 'cpr' ),
					'edit_item'                => __( 'Edit Show Segment', 'cpr' ),
					'new_item'                 => __( 'New Show Segment', 'cpr' ),
					'view_item'                => __( 'View Show Segment', 'cpr' ),
					'view_items'               => __( 'View Show Segments', 'cpr' ),
					'search_items'             => __( 'Search Show Segments', 'cpr' ),
					'not_found'                => __( 'No show segments found', 'cpr' ),
					'not_found_in_trash'       => __( 'No show segments found in Trash', 'cpr' ),
					'parent_item_colon'        => __( 'Parent Show Segment:', 'cpr' ),
					'all_items'                => __( 'Show Segments', 'cpr' ),
					'archives'                 => __( 'Show Segment Archives', 'cpr' ),
					'attributes'               => __( 'Show Segment Attributes', 'cpr' ),
					'insert_into_item'         => __( 'Insert into show segment', 'cpr' ),
					'uploaded_to_this_item'    => __( 'Uploaded to this show segment', 'cpr' ),
					'featured_image'           => __( 'Featured Image', 'cpr' ),
					'set_featured_image'       => __( 'Set featured image', 'cpr' ),
					'remove_featured_image'    => __( 'Remove featured image', 'cpr' ),
					'use_featured_image'       => __( 'Use as featured image', 'cpr' ),
					'filter_items_list'        => __( 'Filter show segments list', 'cpr' ),
					'items_list_navigation'    => __( 'Show Segments list navigation', 'cpr' ),
					'items_list'               => __( 'Show Segments list', 'cpr' ),
					'item_published'           => __( 'Show Segment published.', 'cpr' ),
					'item_published_privately' => __( 'Show Segment published privately.', 'cpr' ),
					'item_reverted_to_draft'   => __( 'Show Segment reverted to draft.', 'cpr' ),
					'item_scheduled'           => __( 'Show Segment scheduled.', 'cpr' ),
					'item_updated'             => __( 'Show Segment updated.', 'cpr' ),
					'menu_name'                => __( 'Show Segments', 'cpr' ),
				],
				'public' => true,
				'show_in_rest' => true,
				'show_in_menu' => false,
				'show_in_admin_bar' => true,
				'supports' => [ 'title', 'thumbnail', 'author', 'editor', 'revisions', 'excerpt', 'custom-fields' ],
				'taxonomies' => [ 'category', 'post_tag' ],
			]
		);
	}
}
$cpr_post_type_show_segment = new Cpr_Post_Type_Show_Segment();
