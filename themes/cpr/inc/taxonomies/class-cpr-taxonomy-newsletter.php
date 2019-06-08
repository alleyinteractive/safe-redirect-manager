<?php
/**
 * Taxonomy for Newsletters
 *
 * @package Cpr
 */

/**
 * Class for the newsletter taxonomy.
 */
class Cpr_Taxonomy_Newsletter extends Cpr_Taxonomy {

	/**
	 * Name of the taxonomy.
	 *
	 * @var string
	 */
	public $name = 'newsletter';

	/**
	 * Object types for this taxonomy.
	 *
	 * @var array
	 */
	public $object_types;


	/**
	 * Build the taxonomy object.
	 */
	public function __construct() {
		$this->object_types = [ 'newsletter-single' ];

		parent::__construct();
	}

	/**
	 * Creates the taxonomy.
	 */
	public function create_taxonomy() {
		register_taxonomy(
			$this->name,
			$this->object_types,
			[
				'labels' => [
					'name'                  => __( 'Newsletters', 'cpr' ),
					'singular_name'         => __( 'Newsletter', 'cpr' ),
					'search_items'          => __( 'Search Newsletters', 'cpr' ),
					'popular_items'         => __( 'Popular Newsletters', 'cpr' ),
					'all_items'             => __( 'All Newsletters', 'cpr' ),
					'parent_item'           => __( 'Parent Newsletter', 'cpr' ),
					'parent_item_colon'     => __( 'Parent Newsletter', 'cpr' ),
					'edit_item'             => __( 'Edit Newsletter', 'cpr' ),
					'view_item'             => __( 'View Newsletter', 'cpr' ),
					'update_item'           => __( 'Update Newsletter', 'cpr' ),
					'add_new_item'          => __( 'Add New Newsletter', 'cpr' ),
					'new_item_name'         => __( 'New Newsletter Name', 'cpr' ),
					'add_or_remove_items'   => __( 'Add or remove newsletters', 'cpr' ),
					'choose_from_most_used' => __( 'Choose from the most used newsletters', 'cpr' ),
					'not_found'             => __( 'No newsletters found', 'cpr' ),
					'no_terms'              => __( 'No newsletters', 'cpr' ),
					'items_list_navigation' => __( 'Newsletters list navigation', 'cpr' ),
					'items_list'            => __( 'Newsletters list', 'cpr' ),
					'back_to_items'         => __( '&larr; Back to Newsletters', 'cpr' ),
					'menu_name'             => __( 'Manage Newsletters', 'cpr' ),
					'name_admin_bar'        => __( 'Newsletters', 'cpr' ),
				],
				'show_admin_column' => true,
				'hierarchical' => true,
				'show_in_rest' => true,
			]
		);
	}
}
$cpr_taxonomy_newsletter = new Cpr_Taxonomy_Newsletter();
