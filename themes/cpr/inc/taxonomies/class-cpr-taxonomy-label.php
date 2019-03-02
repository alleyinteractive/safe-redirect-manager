<?php
/**
 * Taxonomy for Labels
 *
 * @package Cpr
 */

/**
 * Class for the label taxonomy.
 */
class Cpr_Taxonomy_Label extends Cpr_Taxonomy {

	/**
	 * Name of the taxonomy.
	 *
	 * @var string
	 */
	public $name = 'label';

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
		$this->object_types = [ 'album' ];

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
					'name'                  => __( 'Labels', 'cpr' ),
					'singular_name'         => __( 'Label', 'cpr' ),
					'search_items'          => __( 'Search Labels', 'cpr' ),
					'popular_items'         => __( 'Popular Labels', 'cpr' ),
					'all_items'             => __( 'All Labels', 'cpr' ),
					'parent_item'           => __( 'Parent Label', 'cpr' ),
					'parent_item_colon'     => __( 'Parent Label', 'cpr' ),
					'edit_item'             => __( 'Edit Label', 'cpr' ),
					'view_item'             => __( 'View Label', 'cpr' ),
					'update_item'           => __( 'Update Label', 'cpr' ),
					'add_new_item'          => __( 'Add New Label', 'cpr' ),
					'new_item_name'         => __( 'New Label Name', 'cpr' ),
					'add_or_remove_items'   => __( 'Add or remove labels', 'cpr' ),
					'choose_from_most_used' => __( 'Choose from the most used labels', 'cpr' ),
					'not_found'             => __( 'No labels found', 'cpr' ),
					'no_terms'              => __( 'No labels', 'cpr' ),
					'items_list_navigation' => __( 'Labels list navigation', 'cpr' ),
					'items_list'            => __( 'Labels list', 'cpr' ),
					'back_to_items'         => __( '&larr; Back to Labels', 'cpr' ),
					'menu_name'             => __( 'Labels', 'cpr' ),
					'name_admin_bar'        => __( 'Labels', 'cpr' ),
				],
				'show_admin_column' => true,
				'show_in_rest' => true,
			]
		);
	}
}
$cpr_taxonomy_label = new Cpr_Taxonomy_Label();
