<?php
/**
 * Custom post type for Press Releases
 *
 * @package Cpr
 */

/**
 * Class for the press-release post type.
 */
class Cpr_Post_Type_Press_Release extends Cpr_Post_Type {

	/**
	 * Name of the custom post type.
	 *
	 * @var string
	 */
	public $name = 'press-release';

	/**
	 * Creates the post type.
	 */
	public function create_post_type() {
		register_post_type(
			$this->name,
			[
				'labels' => [
					'name'                     => __( 'Press Releases', 'cpr' ),
					'singular_name'            => __( 'Press Release', 'cpr' ),
					'add_new'                  => __( 'Add New Press Release', 'cpr' ),
					'add_new_item'             => __( 'Add New Press Release', 'cpr' ),
					'edit_item'                => __( 'Edit Press Release', 'cpr' ),
					'new_item'                 => __( 'New Press Release', 'cpr' ),
					'view_item'                => __( 'View Press Release', 'cpr' ),
					'view_items'               => __( 'View Press Releases', 'cpr' ),
					'search_items'             => __( 'Search Press Releases', 'cpr' ),
					'not_found'                => __( 'No press releases found', 'cpr' ),
					'not_found_in_trash'       => __( 'No press releases found in Trash', 'cpr' ),
					'parent_item_colon'        => __( 'Parent Press Release:', 'cpr' ),
					'all_items'                => __( 'All Press Releases', 'cpr' ),
					'archives'                 => __( 'Press Release Archives', 'cpr' ),
					'attributes'               => __( 'Press Release Attributes', 'cpr' ),
					'insert_into_item'         => __( 'Insert into press release', 'cpr' ),
					'uploaded_to_this_item'    => __( 'Uploaded to this press release', 'cpr' ),
					'featured_image'           => __( 'Featured Image', 'cpr' ),
					'set_featured_image'       => __( 'Set featured image', 'cpr' ),
					'remove_featured_image'    => __( 'Remove featured image', 'cpr' ),
					'use_featured_image'       => __( 'Use as featured image', 'cpr' ),
					'filter_items_list'        => __( 'Filter press releases list', 'cpr' ),
					'items_list_navigation'    => __( 'Press Releases list navigation', 'cpr' ),
					'items_list'               => __( 'Press Releases list', 'cpr' ),
					'item_published'           => __( 'Press Release published.', 'cpr' ),
					'item_published_privately' => __( 'Press Release published privately.', 'cpr' ),
					'item_reverted_to_draft'   => __( 'Press Release reverted to draft.', 'cpr' ),
					'item_scheduled'           => __( 'Press Release scheduled.', 'cpr' ),
					'item_updated'             => __( 'Press Release updated.', 'cpr' ),
					'menu_name'                => __( 'Press Releases', 'cpr' ),
				],
				'public' => true,
				'has_archive' => 'press-releases',
				'show_in_rest' => true,
				'supports' => [ 'title', 'editor', 'revisions', 'thumbnail', 'excerpt' ],
			]
		);
	}
}
$cpr_post_type_press_release = new Cpr_Post_Type_Press_Release();
