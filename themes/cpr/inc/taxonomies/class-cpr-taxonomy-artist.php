<?php
/**
 * Taxonomy for Artists
 *
 * @package Cpr
 */

/**
 * Class for the artist taxonomy.
 */
class Cpr_Taxonomy_Artist extends Cpr_Taxonomy {

	/**
	 * Name of the taxonomy.
	 *
	 * @var string
	 */
	public $name = 'artist';

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
					'name'                  => __( 'Artists', 'cpr' ),
					'singular_name'         => __( 'Artist', 'cpr' ),
					'search_items'          => __( 'Search Artists', 'cpr' ),
					'popular_items'         => __( 'Popular Artists', 'cpr' ),
					'all_items'             => __( 'All Artists', 'cpr' ),
					'parent_item'           => __( 'Parent Artist', 'cpr' ),
					'parent_item_colon'     => __( 'Parent Artist', 'cpr' ),
					'edit_item'             => __( 'Edit Artist', 'cpr' ),
					'view_item'             => __( 'View Artist', 'cpr' ),
					'update_item'           => __( 'Update Artist', 'cpr' ),
					'add_new_item'          => __( 'Add New Artist', 'cpr' ),
					'new_item_name'         => __( 'New Artist Name', 'cpr' ),
					'add_or_remove_items'   => __( 'Add or remove artists', 'cpr' ),
					'choose_from_most_used' => __( 'Choose from the most used artists', 'cpr' ),
					'not_found'             => __( 'No artists found', 'cpr' ),
					'no_terms'              => __( 'No artists', 'cpr' ),
					'items_list_navigation' => __( 'Artists list navigation', 'cpr' ),
					'items_list'            => __( 'Artists list', 'cpr' ),
					'back_to_items'         => __( '&larr; Back to Artists', 'cpr' ),
					'menu_name'             => __( 'Artists', 'cpr' ),
					'name_admin_bar'        => __( 'Artists', 'cpr' ),
				],
				'show_admin_column' => true,
				'show_in_rest' => true,
			]
		);
	}
}
$cpr_taxonomy_artist = new Cpr_Taxonomy_Artist();
