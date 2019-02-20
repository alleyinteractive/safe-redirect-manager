<?php
/**
 * Taxonomy for Sections
 *
 * @package Cpr
 */

/**
 * Class for the section taxonomy.
 */
class Cpr_Taxonomy_Section extends Cpr_Taxonomy {

	/**
	 * Name of the taxonomy.
	 *
	 * @var string
	 */
	public $name = 'section';

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
		$this->object_types = [ 'post', 'podcast-episode', 'podcast-post' ];

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
					'name'                  => __( 'Sections', 'cpr' ),
					'singular_name'         => __( 'Section', 'cpr' ),
					'search_items'          => __( 'Search Sections', 'cpr' ),
					'popular_items'         => __( 'Popular Sections', 'cpr' ),
					'all_items'             => __( 'All Sections', 'cpr' ),
					'parent_item'           => __( 'Parent Section', 'cpr' ),
					'parent_item_colon'     => __( 'Parent Section', 'cpr' ),
					'edit_item'             => __( 'Edit Section', 'cpr' ),
					'view_item'             => __( 'View Section', 'cpr' ),
					'update_item'           => __( 'Update Section', 'cpr' ),
					'add_new_item'          => __( 'Add New Section', 'cpr' ),
					'new_item_name'         => __( 'New Section Name', 'cpr' ),
					'add_or_remove_items'   => __( 'Add or remove sections', 'cpr' ),
					'choose_from_most_used' => __( 'Choose from the most used sections', 'cpr' ),
					'not_found'             => __( 'No sections found', 'cpr' ),
					'no_terms'              => __( 'No sections', 'cpr' ),
					'items_list_navigation' => __( 'Sections list navigation', 'cpr' ),
					'items_list'            => __( 'Sections list', 'cpr' ),
					'back_to_items'         => __( '&larr; Back to Sections', 'cpr' ),
					'menu_name'             => __( 'Sections', 'cpr' ),
					'name_admin_bar'        => __( 'Sections', 'cpr' ),
				],
				'meta_box_cb' => false,
				'show_admin_column' => true,
				'show_in_rest' => false,
			]
		);
	}
}
$cpr_taxonomy_section = new Cpr_Taxonomy_Section();
