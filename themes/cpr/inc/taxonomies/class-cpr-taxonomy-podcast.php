<?php
/**
 * Taxonomy for Podcasts
 *
 * @package Cpr
 */

/**
 * Class for the podcast taxonomy.
 */
class Cpr_Taxonomy_Podcast extends Cpr_Taxonomy {

	/**
	 * Name of the taxonomy.
	 *
	 * @var string
	 */
	public $name = 'podcast';

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
		$this->object_types = [ 'podcast-episode' ];

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
					'name'                  => __( 'Podcasts', 'cpr' ),
					'singular_name'         => __( 'Podcast', 'cpr' ),
					'search_items'          => __( 'Search Podcasts', 'cpr' ),
					'popular_items'         => __( 'Popular Podcasts', 'cpr' ),
					'all_items'             => __( 'All Podcasts', 'cpr' ),
					'parent_item'           => __( 'Parent Podcast', 'cpr' ),
					'parent_item_colon'     => __( 'Parent Podcast', 'cpr' ),
					'edit_item'             => __( 'Edit Podcast', 'cpr' ),
					'view_item'             => __( 'View Podcast', 'cpr' ),
					'update_item'           => __( 'Update Podcast', 'cpr' ),
					'add_new_item'          => __( 'Add New Podcast', 'cpr' ),
					'new_item_name'         => __( 'New Podcast Name', 'cpr' ),
					'add_or_remove_items'   => __( 'Add or remove podcasts', 'cpr' ),
					'choose_from_most_used' => __( 'Choose from the most used podcasts', 'cpr' ),
					'not_found'             => __( 'No podcasts found', 'cpr' ),
					'no_terms'              => __( 'No podcasts', 'cpr' ),
					'items_list_navigation' => __( 'Podcasts list navigation', 'cpr' ),
					'items_list'            => __( 'Podcasts list', 'cpr' ),
					'back_to_items'         => __( '&larr; Back to Podcasts', 'cpr' ),
					'menu_name'             => __( 'Podcasts', 'cpr' ),
					'name_admin_bar'        => __( 'Podcasts', 'cpr' ),
				],
				'show_admin_column' => true,
				'show_in_rest' => true,
			]
		);
	}
}
$cpr_taxonomy_podcast = new Cpr_Taxonomy_Podcast();
