<?php
/**
 * Custom post type for Albums
 *
 * @package Cpr
 */

/**
 * Class for the album post type.
 */
class Cpr_Post_Type_Album extends Cpr_Post_Type {

	/**
	 * Name of the custom post type.
	 *
	 * @var string
	 */
	public $name = 'album';

	/**
	 * Creates the post type.
	 */
	public function create_post_type() {
		register_post_type(
			$this->name,
			[
				'labels' => [
					'name'                     => __( 'Albums', 'cpr' ),
					'singular_name'            => __( 'Album', 'cpr' ),
					'add_new'                  => __( 'Add New Album', 'cpr' ),
					'add_new_item'             => __( 'Add New Album', 'cpr' ),
					'edit_item'                => __( 'Edit Album', 'cpr' ),
					'new_item'                 => __( 'New Album', 'cpr' ),
					'view_item'                => __( 'View Album', 'cpr' ),
					'view_items'               => __( 'View Albums', 'cpr' ),
					'search_items'             => __( 'Search Albums', 'cpr' ),
					'not_found'                => __( 'No albums found', 'cpr' ),
					'not_found_in_trash'       => __( 'No albums found in Trash', 'cpr' ),
					'parent_item_colon'        => __( 'Parent Album:', 'cpr' ),
					'all_items'                => __( 'All Albums', 'cpr' ),
					'archives'                 => __( 'Album Archives', 'cpr' ),
					'attributes'               => __( 'Album Attributes', 'cpr' ),
					'insert_into_item'         => __( 'Insert into album', 'cpr' ),
					'uploaded_to_this_item'    => __( 'Uploaded to this album', 'cpr' ),
					'featured_image'           => __( 'Featured Image', 'cpr' ),
					'set_featured_image'       => __( 'Set featured image', 'cpr' ),
					'remove_featured_image'    => __( 'Remove featured image', 'cpr' ),
					'use_featured_image'       => __( 'Use as featured image', 'cpr' ),
					'filter_items_list'        => __( 'Filter albums list', 'cpr' ),
					'items_list_navigation'    => __( 'Albums list navigation', 'cpr' ),
					'items_list'               => __( 'Albums list', 'cpr' ),
					'item_published'           => __( 'Album published.', 'cpr' ),
					'item_published_privately' => __( 'Album published privately.', 'cpr' ),
					'item_reverted_to_draft'   => __( 'Album reverted to draft.', 'cpr' ),
					'item_scheduled'           => __( 'Album scheduled.', 'cpr' ),
					'item_updated'             => __( 'Album updated.', 'cpr' ),
					'menu_name'                => __( 'Albums', 'cpr' ),
				],
				'public' => true,
				'publicly_queryable' => false,
				'show_in_rest' => true,
				'menu_icon' => 'dashicons-album',
				'supports' => [ 'title', 'custom-fields' ],
				'taxonomies' => [ 'artist', 'label' ],
			]
		);
	}
}
$cpr_post_type_album = new Cpr_Post_Type_Album();
