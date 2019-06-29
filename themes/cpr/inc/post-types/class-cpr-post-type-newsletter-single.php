<?php
/**
 * Custom post type for Newsletters
 *
 * @package Cpr
 */

/**
 * Class for the newsletter-single post type.
 */
class Cpr_Post_Type_Newsletter_Single extends Cpr_Post_Type {

	/**
	 * Name of the custom post type.
	 *
	 * @var string
	 */
	public $name = 'newsletter-single';

	/**
	 * Creates the post type.
	 */
	public function create_post_type() {
		register_post_type(
			$this->name,
			[
				'labels' => [
					'name'                     => __( 'Newsletters', 'cpr' ),
					'singular_name'            => __( 'Newsletter', 'cpr' ),
					'add_new'                  => __( 'Add New Newsletter', 'cpr' ),
					'add_new_item'             => __( 'Add New Newsletter', 'cpr' ),
					'edit_item'                => __( 'Edit Newsletter', 'cpr' ),
					'new_item'                 => __( 'New Newsletter', 'cpr' ),
					'view_item'                => __( 'View Newsletter', 'cpr' ),
					'view_items'               => __( 'View Newsletters', 'cpr' ),
					'search_items'             => __( 'Search Newsletters', 'cpr' ),
					'not_found'                => __( 'No newsletters found', 'cpr' ),
					'not_found_in_trash'       => __( 'No newsletters found in Trash', 'cpr' ),
					'parent_item_colon'        => __( 'Parent Newsletter:', 'cpr' ),
					'all_items'                => __( 'Newsletters', 'cpr' ),
					'archives'                 => __( 'Newsletter Archives', 'cpr' ),
					'attributes'               => __( 'Newsletter Attributes', 'cpr' ),
					'insert_into_item'         => __( 'Insert into newsletter', 'cpr' ),
					'uploaded_to_this_item'    => __( 'Uploaded to this newsletter', 'cpr' ),
					'featured_image'           => __( 'Featured Image', 'cpr' ),
					'set_featured_image'       => __( 'Set featured image', 'cpr' ),
					'remove_featured_image'    => __( 'Remove featured image', 'cpr' ),
					'use_featured_image'       => __( 'Use as featured image', 'cpr' ),
					'filter_items_list'        => __( 'Filter newsletters list', 'cpr' ),
					'items_list_navigation'    => __( 'Newsletters list navigation', 'cpr' ),
					'items_list'               => __( 'Newsletters list', 'cpr' ),
					'item_published'           => __( 'Newsletter published.', 'cpr' ),
					'item_published_privately' => __( 'Newsletter published privately.', 'cpr' ),
					'item_reverted_to_draft'   => __( 'Newsletter reverted to draft.', 'cpr' ),
					'item_scheduled'           => __( 'Newsletter scheduled.', 'cpr' ),
					'item_updated'             => __( 'Newsletter updated.', 'cpr' ),
					'menu_name'                => __( 'Newsletters', 'cpr' ),
				],
				'public' => true,
				'show_in_rest' => true,
				'supports' => [ 'title', 'revisions', 'excerpt', 'thumbnail', 'author', 'custom-fields' ],
				'taxonomies' => [ 'newsletter' ],
			]
		);
	}
}
$cpr_post_type_newsletter_single = new Cpr_Post_Type_Newsletter_Single();
