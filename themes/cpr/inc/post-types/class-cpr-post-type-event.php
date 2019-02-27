<?php
/**
 * Custom post type for Events
 *
 * @package Cpr
 */

/**
 * Class for the event post type.
 */
class Cpr_Post_Type_Event extends Cpr_Post_Type {

	/**
	 * Name of the custom post type.
	 *
	 * @var string
	 */
	public $name = 'event';

	/**
	 * Creates the post type.
	 */
	public function create_post_type() {
		register_post_type(
			$this->name,
			[
				'labels' => [
					'name'                     => __( 'Events', 'cpr' ),
					'singular_name'            => __( 'Event', 'cpr' ),
					'add_new'                  => __( 'Add New Event', 'cpr' ),
					'add_new_item'             => __( 'Add New Event', 'cpr' ),
					'edit_item'                => __( 'Edit Event', 'cpr' ),
					'new_item'                 => __( 'New Event', 'cpr' ),
					'view_item'                => __( 'View Event', 'cpr' ),
					'view_items'               => __( 'View Events', 'cpr' ),
					'search_items'             => __( 'Search Events', 'cpr' ),
					'not_found'                => __( 'No events found', 'cpr' ),
					'not_found_in_trash'       => __( 'No events found in Trash', 'cpr' ),
					'parent_item_colon'        => __( 'Parent Event:', 'cpr' ),
					'all_items'                => __( 'All Events', 'cpr' ),
					'archives'                 => __( 'Event Archives', 'cpr' ),
					'attributes'               => __( 'Event Attributes', 'cpr' ),
					'insert_into_item'         => __( 'Insert into event', 'cpr' ),
					'uploaded_to_this_item'    => __( 'Uploaded to this event', 'cpr' ),
					'featured_image'           => __( 'Featured Image', 'cpr' ),
					'set_featured_image'       => __( 'Set featured image', 'cpr' ),
					'remove_featured_image'    => __( 'Remove featured image', 'cpr' ),
					'use_featured_image'       => __( 'Use as featured image', 'cpr' ),
					'filter_items_list'        => __( 'Filter events list', 'cpr' ),
					'items_list_navigation'    => __( 'Events list navigation', 'cpr' ),
					'items_list'               => __( 'Events list', 'cpr' ),
					'item_published'           => __( 'Event published.', 'cpr' ),
					'item_published_privately' => __( 'Event published privately.', 'cpr' ),
					'item_reverted_to_draft'   => __( 'Event reverted to draft.', 'cpr' ),
					'item_scheduled'           => __( 'Event scheduled.', 'cpr' ),
					'item_updated'             => __( 'Event updated.', 'cpr' ),
					'menu_name'                => __( 'Events', 'cpr' ),
				],
				'public' => true,
				'show_in_rest' => true,
				'menu_icon' => 'dashicons-calendar-alt',
				'supports' => [ 'title', 'thumbnail', 'editor', 'revisions', 'excerpt', 'custom-fields' ],
				'taxonomies' => [ 'category', 'post_tag', 'section' ],
			]
		);
	}
}
$cpr_post_type_event = new Cpr_Post_Type_Event();
