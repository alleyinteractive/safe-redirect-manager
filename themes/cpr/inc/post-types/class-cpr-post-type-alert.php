<?php
/**
 * Custom post type for Alerts
 *
 * @package Cpr
 */

/**
 * Class for the alert post type.
 */
class Cpr_Post_Type_Alert extends Cpr_Post_Type {

	/**
	 * Name of the custom post type.
	 *
	 * @var string
	 */
	public $name = 'alert';

	/**
	 * Creates the post type.
	 */
	public function create_post_type() {
		register_post_type(
			$this->name,
			[
				'labels' => [
					'name'                     => __( 'Alerts', 'cpr' ),
					'singular_name'            => __( 'Alert', 'cpr' ),
					'add_new'                  => __( 'Add New Alert', 'cpr' ),
					'add_new_item'             => __( 'Add New Alert', 'cpr' ),
					'edit_item'                => __( 'Edit Alert', 'cpr' ),
					'new_item'                 => __( 'New Alert', 'cpr' ),
					'view_item'                => __( 'View Alert', 'cpr' ),
					'view_items'               => __( 'View Alerts', 'cpr' ),
					'search_items'             => __( 'Search Alerts', 'cpr' ),
					'not_found'                => __( 'No alerts found', 'cpr' ),
					'not_found_in_trash'       => __( 'No alerts found in Trash', 'cpr' ),
					'parent_item_colon'        => __( 'Parent Alert:', 'cpr' ),
					'all_items'                => __( 'All Alerts', 'cpr' ),
					'archives'                 => __( 'Alert Archives', 'cpr' ),
					'attributes'               => __( 'Alert Attributes', 'cpr' ),
					'insert_into_item'         => __( 'Insert into alert', 'cpr' ),
					'uploaded_to_this_item'    => __( 'Uploaded to this alert', 'cpr' ),
					'featured_image'           => __( 'Featured Image', 'cpr' ),
					'set_featured_image'       => __( 'Set featured image', 'cpr' ),
					'remove_featured_image'    => __( 'Remove featured image', 'cpr' ),
					'use_featured_image'       => __( 'Use as featured image', 'cpr' ),
					'filter_items_list'        => __( 'Filter alerts list', 'cpr' ),
					'items_list_navigation'    => __( 'Alerts list navigation', 'cpr' ),
					'items_list'               => __( 'Alerts list', 'cpr' ),
					'item_published'           => __( 'Alert published.', 'cpr' ),
					'item_published_privately' => __( 'Alert published privately.', 'cpr' ),
					'item_reverted_to_draft'   => __( 'Alert reverted to draft.', 'cpr' ),
					'item_scheduled'           => __( 'Alert scheduled.', 'cpr' ),
					'item_updated'             => __( 'Alert updated.', 'cpr' ),
					'menu_name'                => __( 'Alerts', 'cpr' ),
				],
				'public' => true,
				'publicly_queryable' => false,
				'has_single' => false,
				'show_in_rest' => true,
				'menu_icon' => 'dashicons-megaphone',
				'supports' => [ 'title' ],
			]
		);
	}
}
$cpr_post_type_alert = new Cpr_Post_Type_Alert();
