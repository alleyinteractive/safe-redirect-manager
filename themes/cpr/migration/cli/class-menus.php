<?php
/**
 * Menu CLI scripts for CPR.
 *
 * @package CPR
 */

namespace CPR\Migration;

use WP_CLI;

/**
 * Menus CLI command.
 */
class Menus extends \CLI_Command {

	/**
	 * Run all menu commands.
	 *
	 * ## EXAMPLES
	 *
	 * wp cpr-menus run_all
	 */
	public function run_all() {
		WP_CLI::runcommand( 'cpr-menus delete_all' );
		WP_CLI::runcommand( 'cpr-menus setup_all' );
	}

	/**
	 * Delete all menus.
	 *
	 * ## EXAMPLES
	 *
	 * wp cpr-menus delete_all
	 */
	public function delete_all() {

		// Delete all location mappings.
		set_theme_mod( 'nav_menu_locations', [] );

		// Loop through all nav menus and delete them.
		array_map(
			function( $menu ) {
				wp_delete_nav_menu( $menu->slug );
			},
			get_terms( 'nav_menu', [ 'hide_empty' => false ] )
		);

		// Display success message.
		WP_CLI::success( 'Removed existing menus.' );
	}

	/**
	 * Setup all new menus.
	 *
	 * ## EXAMPLES
	 *
	 * wp cpr-menus setup_all
	 */
	public function setup_all() {

		// Get locations and begin to track the position assignments.
		$menu_locations            = get_registered_nav_menus();
		$menu_position_assignments = [];

		// Loop through all new menu arrays to register new menu and menu
		// items.
		array_map(
			function( $new_menu ) use ( &$menu_position_assignments ) {

				// Create menu.
				$menu_id = wp_create_nav_menu( $new_menu['name'] );
				WP_CLI::success( "Created menu {$new_menu['name']} as {$menu_id}" );

				// Add items.
				foreach ( $new_menu['items'] as $item ) {
					wp_update_nav_menu_item( $menu_id, 0, $item );
				}

				// Save this menu to the proper location.
				$menu_position_assignments[ $new_menu['position'] ] = $menu_id;
			},
			[
				$this->get_primary_navigation(),

				// Sections.
				$this->get_header_navigation(),
				$this->get_news_navigation(),
				$this->get_classical_navigation(),
				$this->get_indie_navigation(),

				// Footers.
				$this->get_first_footer(),
				$this->get_second_footer(),
				$this->get_third_footer(),
				$this->get_fourth_footer(),
			]
		);

		// Update menu locations.
		set_theme_mod( 'nav_menu_locations', $menu_position_assignments );

		WP_CLI::success( 'Mapped menus to menu locations.' );
	}

	/**
	 * Helper to get the array shape needed to insert nav menu items.
	 *
	 * @param mixed $object Object.
	 * @return array|null
	 */
	protected function get_menu_item_array_by_object( $object ) {

		// Determine what kind of object we're dealing with.
		switch ( $object ) {
			case $object instanceof \WP_Error:
			default:
				return null;

			case $object instanceof \WP_Term:
				return [
					'menu-item-title'     => $object->name,
					'menu-item-type'      => 'taxonomy',
					'menu-item-status'    => 'publish',
					'menu-item-object'    => $object->taxonomy,
					'menu-item-object-id' => $object->term_id,
				];

			case $object instanceof \WP_Post:
				return [
					'menu-item-title'     => $object->post_title,
					'menu-item-type'      => 'post_type',
					'menu-item-status'    => 'publish',
					'menu-item-object'    => $object->post_type,
					'menu-item-object-id' => $object->ID,
				];
		}
	}

	/**
	 * Get the array to build the header nav.
	 *
	 * @return array
	 */
	protected function get_header_navigation() {
		return [
			'name'     => 'Header Navigation',
			'position' => 'header',
			'items'    => array_filter(
				[
					// News.
					// Government & Politics.
					// Money.
					// Arts.
					// Culture.
					// Environment.
					// Support CPR.
					$this->get_menu_item_array_by_object( get_term_by( 'slug', 'news', 'category' ) ),
					$this->get_menu_item_array_by_object( get_term_by( 'slug', 'governance', 'category' ) ),
					$this->get_menu_item_array_by_object( get_term_by( 'slug', 'money', 'category' ) ),
					$this->get_menu_item_array_by_object( get_term_by( 'slug', 'business', 'category' ) ),
					$this->get_menu_item_array_by_object( get_term_by( 'slug', 'energy-and-environment', 'category' ) ),
					$this->get_menu_item_array_by_object( get_page_by_path( 'support' ) ),
				]
			),
		];
	}

	/**
	 * Get the array to build the primary nav.
	 *
	 * @return array
	 */
	protected function get_primary_navigation() {
		return [
			'name'     => 'Primary Navigation',
			'position' => 'primary-navigation',
			'items'    => array_filter(
				[
					// News.
					// Government & Politics.
					// Money.
					// Arts.
					// Culture.
					// Environment.
					// Education.
					// Health.
					// Justice.
					// Shows & Podcasts.
					// Classical.
					// OpenAir.
					// Support CPR.
					// About.
					[
						'menu-item-title'  => 'News',
						'menu-item-url'    => '/news/',
						'menu-item-status' => 'publish',
						'menu-item-type'   => 'custom',
					],
					$this->get_menu_item_array_by_object( get_term_by( 'slug', 'governance', 'category' ) ),
					$this->get_menu_item_array_by_object( get_term_by( 'slug', 'money', 'category' ) ),
					$this->get_menu_item_array_by_object( get_term_by( 'slug', 'business', 'category' ) ),
					$this->get_menu_item_array_by_object( get_term_by( 'slug', 'energy-and-environment', 'category' ) ),
					[
						'menu-item-title'  => 'Shows and Podcasts',
						'menu-item-url'    => '/shows-and-podcasts/',
						'menu-item-status' => 'publish',
						'menu-item-type'   => 'custom',
					],
					[
						'menu-item-title'  => 'Classical',
						'menu-item-url'    => '/classical/',
						'menu-item-status' => 'publish',
						'menu-item-type'   => 'custom',
					],
					[
						'menu-item-title'  => 'Indie',
						'menu-item-url'    => '/indie/',
						'menu-item-status' => 'publish',
						'menu-item-type'   => 'custom',
					],
					$this->get_menu_item_array_by_object( get_page_by_path( 'support' ) ),
					$this->get_menu_item_array_by_object( get_page_by_path( 'about' ) ),
				]
			),
		];
	}

	/**
	 * Get the array to build the news nav.
	 *
	 * @return array
	 */
	protected function get_news_navigation() {
		return [
			'name'     => 'News Section',
			'position' => 'news',
			'items'    => array_filter(
				[

					[
						'menu-item-title'  => 'Shows and Podcasts',
						'menu-item-url'    => '/shows-and-podcasts/',
						'menu-item-status' => 'publish',
						'menu-item-type'   => 'custom',
					],
				]
			),
		];
	}

	/**
	 * Get the array to build the classical nav.
	 *
	 * @return array
	 */
	protected function get_classical_navigation() {
		return [
			'name'     => 'Classical Section',
			'position' => 'classical',
			'items'    => array_filter(
				[

					[
						'menu-item-title'  => 'Shows and Podcasts',
						'menu-item-url'    => '/shows-and-podcasts/',
						'menu-item-status' => 'publish',
						'menu-item-type'   => 'custom',
					],
				]
			),
		];
	}

	/**
	 * Get the array to build the indie nav.
	 *
	 * @return array
	 */
	protected function get_indie_navigation() {
		return [
			'name'     => 'Indie section',
			'position' => 'indie',
			'items'    => array_filter(
				[
					[
						'menu-item-title'  => 'Where to Listen',
						'menu-item-url'    => '/where-to-listen/',
						'menu-item-status' => 'publish',
						'menu-item-type'   => 'custom',
					],
					[
						'menu-item-title'  => 'Calendar',
						'menu-item-url'    => '/calendar/',
						'menu-item-status' => 'publish',
						'menu-item-type'   => 'custom',
					],
					[
						'menu-item-title'  => 'Schedule',
						'menu-item-url'    => '/schedule/',
						'menu-item-status' => 'publish',
						'menu-item-type'   => 'custom',
					],
				]
			),
		];
	}

	/**
	 * Get the array to build the first footer.
	 *
	 * @return array
	 */
	protected function get_first_footer() {
		return [
			'name'     => 'Footer - CPR',
			'position' => 'footer-1',
			'items'    => array_filter(
				[

					[
						'menu-item-title'  => 'Where to Listen',
						'menu-item-url'    => '/where-to-listen/',
						'menu-item-status' => 'publish',
						'menu-item-type'   => 'custom',
					],
				]
			),
		];
	}

	/**
	 * Get the array to build the second footer.
	 *
	 * @return array
	 */
	protected function get_second_footer() {
		return [
			'name'     => 'Footer - News',
			'position' => 'footer-2',
			'items'    => array_filter(
				[

					[
						'menu-item-title'  => 'Schedule',
						'menu-item-url'    => '/schedule/',
						'menu-item-status' => 'publish',
						'menu-item-type'   => 'custom',
					],
				]
			),
		];
	}

	/**
	 * Get the array to build the third footer.
	 *
	 * @return array
	 */
	protected function get_third_footer() {
		return [
			'name'     => 'Footer - Classical',
			'position' => 'footer-3',
			'items'    => array_filter(
				[

					[
						'menu-item-title'  => 'Playlist',
						'menu-item-url'    => '/playlist/',
						'menu-item-status' => 'publish',
						'menu-item-type'   => 'custom',
					],
				]
			),
		];
	}

	/**
	 * Get the array to build the fourth footer.
	 *
	 * @return array
	 */
	protected function get_fourth_footer() {
		return [
			'name'     => 'Footer - Indie',
			'position' => 'footer-4',
			'items'    => array_filter(
				[

					[
						'menu-item-title'  => 'Playlist',
						'menu-item-url'    => '/playlist/',
						'menu-item-status' => 'publish',
						'menu-item-type'   => 'custom',
					],
				]
			),
		];
	}
}
WP_CLI::add_command( 'cpr-menus', __NAMESPACE__ . '\Menus' );
