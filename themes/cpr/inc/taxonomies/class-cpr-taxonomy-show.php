<?php
/**
 * Taxonomy for Shows
 *
 * @package Cpr
 */

/**
 * Class for the show taxonomy.
 */
class Cpr_Taxonomy_Show extends Cpr_Taxonomy {

	/**
	 * Name of the taxonomy.
	 *
	 * @var string
	 */
	public $name = 'show';

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
		$this->object_types = [ 'show-episode' ];

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
					'name'                  => __( 'Shows', 'cpr' ),
					'singular_name'         => __( 'Show', 'cpr' ),
					'search_items'          => __( 'Search Shows', 'cpr' ),
					'popular_items'         => __( 'Popular Shows', 'cpr' ),
					'all_items'             => __( 'All Shows', 'cpr' ),
					'parent_item'           => __( 'Parent Show', 'cpr' ),
					'parent_item_colon'     => __( 'Parent Show', 'cpr' ),
					'edit_item'             => __( 'Edit Show', 'cpr' ),
					'view_item'             => __( 'View Show', 'cpr' ),
					'update_item'           => __( 'Update Show', 'cpr' ),
					'add_new_item'          => __( 'Add New Show', 'cpr' ),
					'new_item_name'         => __( 'New Show Name', 'cpr' ),
					'add_or_remove_items'   => __( 'Add or remove shows', 'cpr' ),
					'choose_from_most_used' => __( 'Choose from the most used shows', 'cpr' ),
					'not_found'             => __( 'No shows found', 'cpr' ),
					'no_terms'              => __( 'No shows', 'cpr' ),
					'items_list_navigation' => __( 'Shows list navigation', 'cpr' ),
					'items_list'            => __( 'Shows list', 'cpr' ),
					'back_to_items'         => __( '&larr; Back to Shows', 'cpr' ),
					'menu_name'             => __( 'Shows', 'cpr' ),
					'name_admin_bar'        => __( 'Shows', 'cpr' ),
				],
				'show_admin_column' => true,
				'show_in_rest' => true,
			]
		);
	}
}
$cpr_taxonomy_show = new Cpr_Taxonomy_Show();
