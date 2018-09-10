<?php
/**
 * Easily create an manage landing pages
 *
 * @package Cpr
 */

namespace Cpr;

/**
 * Class defining landing pages.
 */
class Landing_Pages {

	/**
	 * Landing page options.
	 *
	 * @var array
	 */
	private $options = [];

	/**
	 * Landing page post type.
	 *
	 * @var string
	 */
	private $post_type = 'landing-page';

	/**
	 * Store the singleton instance.
	 *
	 * @var Landing_Pages
	 */
	private static $instance;

	/**
	 * Build the object.
	 *
	 * @access private
	 */
	private function __construct() {
		/* Don't do anything, needs to be initialized via instance() method */
	}

	/**
	 * Get the Singleton instance.
	 *
	 * @return Landing_Pages
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Landing_Pages();
			self::$instance->setup();
		}
		return self::$instance;
	}

	/**
	 * Register hooks and filters, etc.
	 */
	public function setup() {
		$this->setup_options();

		// Register post type.
		add_action( 'init', [ $this, 'register_post_type' ] );

		// Use landing pages.
		add_filter( 'pre_get_posts', [ $this, 'pre_get_posts' ] );
		add_filter( 'query_vars', [ $this, 'query_vars' ] );

		// Disable Gutenberg for landing pages.
		add_filter( 'gutenberg_can_edit_post_type', [ $this, 'disable_gutenberg' ], 10, 2 );

		// Landing page FM support.
		add_action( 'fm_post_landing-page', [ $this, 'landing_page_meta' ] );

	}

	/**
	 * Setup landing page options.
	 */
	public function setup_options() {

		// Setup landing page options.
		$options = apply_filters( 'landing_page_options', $this->options );

		// Loop through options and validate.
		foreach ( $options as $key => $option ) {

			// Validate and force types.
			$option['label'] = (string) ( $option['label'] ?? '' );
			$option['slugs'] = (array) ( $option['slugs'] ?? [] );

			// Add rewrite rules for each type.
			foreach ( $option['slugs'] as $slug ) {

				// Change slug format to rewrite rule (except for root queries).
				if ( '' !== $slug && '/' !== $slug ) {
					$slug = "($slug)/?$";
				}

				// Add rewrite for a top-level rule for this landing page type.
				add_rewrite_rule(
					$slug,
					add_query_arg(
						[
							'dispatch'          => 'landing-page',
							'landing-page-type' => '$matches[1]',
						],
						'index.php'
					),
					'top'
				);
			}
		}

		$this->options = $options;
	}

	/**
	 * Get options.
	 *
	 * @return array Landing page options.
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * Get options for a select field.
	 *
	 * @return array Options to populate a Fieldmanager_Select field.
	 */
	public function get_select_options() {
		$options = $this->get_options();
		foreach ( $options as $key => $option ) {
			$options[ $key ] = $option['label'];
		}
		return $options;
	}

	/**
	 * Get types.
	 *
	 * @return array Landing page option keys.
	 */
	public function get_types() {
		return array_keys( $this->get_options() );
	}

	/**
	 * Register the `landing-page` post type.
	 */
	public function register_post_type() {
		$args = [
			'label'        => __( 'Landing Pages', 'cpr' ),
			'public'       => true,
			'show_in_rest' => true,
			'show_in_menu' => true,
			'has_archive'  => false,
			'menu_icon'    => 'dashicons-admin-multisite',
			'supports'     => [
				'revisions',
				'title',
			],
		];

		$args = apply_filters( 'landing_page_post_type_args', $args );

		register_post_type( $this->post_type, $args );
	}

	/**
	 * Get the landing page post.
	 *
	 * @param  \WP_Query $wp_query WP_Query object.
	 */
	public function pre_get_posts( $wp_query ) {
		if (
			'landing-page' === $wp_query->get( 'dispatch' )
			&& ! $wp_query->is_admin()
		) {
			$wp_query->set( 'meta_key', 'landing_page_type' );
			$wp_query->set( 'meta_value', $wp_query->get( 'landing-page-type' ) );
			$wp_query->set( 'post_status', 'publish' );
			$wp_query->set( 'post_type', 'landing-page' );
			$wp_query->set( 'posts_per_page', 1 );
		}
	}
	/**
	 * Add custom query vars.
	 *
	 * @param array $vars Array of current query vars.
	 * @return array $vars Array of query vars.
	 */
	public function query_vars( $vars ) {
		$vars[] = 'dispatch';
		$vars[] = 'landing-page-type';
		return $vars;
	}

	/**
	 * Disable Gutenberg for this post type.
	 *
	 * @param  boolean $enabled   Is Gutenberg enabled.
	 * @param  string  $post_type Post type.
	 * @return string Post type.
	 */
	public function disable_gutenberg( $enabled, $post_type ) {
		if ( $this->post_type === $post_type ) {
			$enabled = false;
		}
		return $enabled;
	}

	/**
	 * Setup landing page meta.
	 *
	 * @todo  Add a check to ensure Fieldmanager is available.
	 */
	public function landing_page_meta() {
		$children = [
			'landing_page_type' => new \Fieldmanager_Select(
				[
					'first_empty' => true,
					'options'     => $this->get_select_options(),
				]
			),
		];

		$children = apply_filters( 'landing_page_fm_children', $children );

		// Build FM fields.
		$fm = new \Fieldmanager_Group(
			[
				'name'           => 'landing-page',
				'serialize_data' => false,
				'add_to_prefix'  => false,
				'children'       => $children,
			]
		);

		// Add meta box.
		$fm->add_meta_box(
			__( 'Landing Page Settings', 'cpr' ),
			[ $this->post_type ],
			'normal',
			'high'
		);
	}
}
