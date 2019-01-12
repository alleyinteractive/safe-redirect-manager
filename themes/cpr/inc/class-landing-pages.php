<?php
/**
 * Easily create and manage landing pages.
 *
 * @package CPR
 */

namespace CPR;

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

		// Modify the post type.
		$this->post_type = apply_filters( 'landing_page_post_type', $this->post_type );

		// Register post type.
		add_action( 'init', [ $this, 'register_post_type' ] );

		// Use landing pages.
		add_filter( 'pre_get_posts', [ $this, 'pre_get_posts' ] );
		add_filter( 'query_vars', [ $this, 'query_vars' ] );

		// Ensure that the permalink is always correct.
		add_filter( 'post_link', [ $this, 'modify_permalink' ], 10, 2 );
		add_filter( 'page_link', [ $this, 'modify_permalink' ], 10, 2 );
		add_filter( 'post_type_link', [ $this, 'modify_permalink' ], 10, 2 );

		// Landing page FM support.
		add_action( 'fm_post_' . $this->post_type, [ $this, 'landing_page_meta' ] );
	}

	/**
	 * Setup landing page options.
	 */
	public function setup_options() {

		// Setup landing page options.
		$this->options = apply_filters( 'landing_page_options', $this->options );

		// Loop through options and validate.
		foreach ( $this->options as $key => $option ) {

			// Validate and force types.
			$option['label'] = (string) ( $option['label'] ?? '' );
			$option['slugs'] = (array) ( $option['slugs'] ?? [] );

			// Add rewrite rules for each type.
			foreach ( $option['slugs'] as $slug ) {

				switch ( $slug ) {
					case '':
					case '/':
						// Homepage specific routing.
						add_rewrite_rule(
							'/?$',
							add_query_arg(
								[
									'dispatch'          => 'landing-page',
									'landing-page-type' => 'homepage',
								],
								'index.php'
							),
							'bottom'
						);
						break;

					default:
						add_rewrite_rule(
							"($slug)/?$",
							add_query_arg(
								[
									'dispatch'          => 'landing-page',
									'landing-page-type' => '$matches[1]',
								],
								'index.php'
							),
							'top'
						);
						break;
				}
			}
		}
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
	 * Register a new post type for landing pages.
	 */
	public function register_post_type() {

		// Ensure this is a new post type.
		if ( post_type_exists( $this->post_type ) ) {
			return;
		}

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
		if ( $wp_query->is_admin() || 'landing-page' !== $wp_query->get( 'dispatch' ) ) {
			return;
		}

		// Use a landing page.
		$wp_query->set( 'meta_key', 'landing_page_type' );
		$wp_query->set( 'meta_value', $wp_query->get( 'landing-page-type' ) );
		$wp_query->set( 'post_status', 'publish' );
		$wp_query->set( 'post_type', $this->post_type );
		$wp_query->set( 'posts_per_page', 1 );
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
	 * Modify the permalink for the latest published landing page of any type.
	 *
	 * @param  string       $url  Post permalink.
	 * @param  int|\WP_Post $post Post object or ID.
	 * @return string
	 */
	public function modify_permalink( $url, $post ) : string {

		// Ensure we're modifying the correct post type.
		if ( get_post_type( $post ) !== $this->post_type ) {
			return $url;
		}

		// If we only have the ID, use it to get the post object.
		if ( ! $post instanceof \WP_Post ) {
			$post = get_post( absint( $post ) );

			// Validate and return if needed.
			if ( ! $post instanceof \WP_Post ) {
				return $url;
			}
		}

		// Get the landing page type.
		$landing_page_type = get_post_meta( $post->ID, 'landing_page_type', true );
		if ( empty( $landing_page_type ) ) {
			return $url;
		}

		// Ensure this post is the latest published of the type.
		if ( $post->ID !== $this->get_latest_landing_page_id_by_type( $landing_page_type ) ) {
			return $url;
		}

		// Get the first slug for this landing page type.
		$slug = $this->options[ $landing_page_type ]['slugs'][0] ?? '';
		if ( empty( $slug ) ) {
			return $url;
		}

		// Return a proper slug.
		return trailingslashit( home_url( $slug ) );
	}

	/**
	 * Get the ID for the latest landing page of a type.
	 *
	 * @param  string $type Landing page type.
	 * @return int Post ID.
	 */
	public function get_latest_landing_page_id_by_type( string $type ) : int {
		$query = new \WP_Query(
			[
				'fields'         => 'ids',
				'meta_key'       => 'landing_page_type',
				'meta_value'     => $type,
				'post_status'    => 'publish',
				'post_type'      => $this->post_type,
				'posts_per_page' => 1,
			]
		);

		return $query->posts[0] ?? 0;
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
