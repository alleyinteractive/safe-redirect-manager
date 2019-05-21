<?php
/**
 * Taxonomy for Underwriter Categories
 *
 * @package Cpr
 */

/**
 * Class for the underwriter-category taxonomy.
 */
class Cpr_Taxonomy_Underwriter_Category extends Cpr_Taxonomy {

	/**
	 * Name of the taxonomy.
	 *
	 * @var string
	 */
	public $name = 'underwriter-category';

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
		$this->object_types = [ 'underwriter' ];

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
					'name'                  => __( 'Underwriter Categories', 'cpr' ),
					'singular_name'         => __( 'Underwriter Category', 'cpr' ),
					'search_items'          => __( 'Search Underwriter Categories', 'cpr' ),
					'popular_items'         => __( 'Popular Underwriter Categories', 'cpr' ),
					'all_items'             => __( 'All Underwriter Categories', 'cpr' ),
					'parent_item'           => __( 'Parent Underwriter Category', 'cpr' ),
					'parent_item_colon'     => __( 'Parent Underwriter Category', 'cpr' ),
					'edit_item'             => __( 'Edit Underwriter Category', 'cpr' ),
					'view_item'             => __( 'View Underwriter Category', 'cpr' ),
					'update_item'           => __( 'Update Underwriter Category', 'cpr' ),
					'add_new_item'          => __( 'Add New Underwriter Category', 'cpr' ),
					'new_item_name'         => __( 'New Underwriter Category Name', 'cpr' ),
					'add_or_remove_items'   => __( 'Add or remove underwriter categories', 'cpr' ),
					'choose_from_most_used' => __( 'Choose from the most used underwriter categories', 'cpr' ),
					'not_found'             => __( 'No underwriter categories found', 'cpr' ),
					'no_terms'              => __( 'No underwriter categories', 'cpr' ),
					'items_list_navigation' => __( 'Underwriter Categories list navigation', 'cpr' ),
					'items_list'            => __( 'Underwriter Categories list', 'cpr' ),
					'back_to_items'         => __( '&larr; Back to Underwriter Categories', 'cpr' ),
					'menu_name'             => __( 'Underwriter Categories', 'cpr' ),
					'name_admin_bar'        => __( 'Underwriter Categories', 'cpr' ),
				],
				'show_admin_column' => true,
				'show_in_rest' => true,
			]
		);
	}
}
$cpr_taxonomy_underwriter_category = new Cpr_Taxonomy_Underwriter_Category();
