<?php
/**
 * Class that links a new custom post type and term together.
 *
 * @package Cpr
 */

namespace Cpr;

/**
 * Term Post Link
 */
class Term_Post_Link {

	/**
	 * Taxonomy.
	 *
	 * @var string
	 */
	public $taxonomy = '';

	/**
	 * Post type.
	 *
	 * @var string
	 */
	public $post_type = '';

	/**
	 * All Taxonomies with a term post link setup.
	 *
	 * @var array
	 */
	public static $taxonomies = [];

	/**
	 * All Post Types with a term post link setup.
	 *
	 * @var array
	 */
	public static $post_types = [];

	/**
	 * Constructor.
	 *
	 * @param string $taxonomy  Taxonomy to use.
	 * @param string $post_type Post type to use.
	 */
	public function __construct( $taxonomy, $post_type ) {

		// Store vars for this instance.
		$this->taxonomy  = $taxonomy;
		$this->post_type = $post_type;

		// Add to record for all instances.
		self::$taxonomies[] = $taxonomy;
		self::$post_types[] = $post_type;

		// Validate our taxonomy and post type.
		add_action(
			'wp_loaded',
			function() use ( $taxonomy, $post_type ) {
				if ( ! taxonomy_exists( $taxonomy ) || ! post_type_exists( $post_type ) ) {
					wp_die( esc_html__( 'Missing tax or post type', 'cpr' ) );
				}

				// Create linked posts for the first time.
				$this->create_posts();
			}
		);

		// Setup.
		add_action( 'init', [ $this, 'setup' ] );
	}

	/**
	 * Setup filters and actions for this class.
	 */
	public function setup() {

		// Admin screen redirects.
		if ( is_admin() ) {
			add_action( 'current_screen', [ $this, 'admin_screen_redirects' ] );
		}

		// Create post on new taxonomy.
		add_action( 'created_' . $this->taxonomy, [ $this, 'create_post' ] );
		add_action( 'delete_' . $this->taxonomy, [ $this, 'delete_post' ] );

		// Add row action.
		add_filter( $this->taxonomy . '_row_actions', [ $this, 'term_row_actions' ], 10, 2 );

		// On post save.
		add_action( 'save_post', [ $this, 'save_post' ] );

		// On term meta.
		add_filter( 'get_term_metadata', [ $this, 'get_term_metadata' ], 10, 4 );

		// Fix permalink.
		add_filter( 'post_type_link', [ $this, 'modify_permalink' ], 10, 2 );

		// Remove description from default term.
		add_filter( 'manage_edit-' . $this->taxonomy . '_columns', [ $this, 'modify_columns' ] );
	}

	/**
	 * Static helper to get the term_id from a post.
	 *
	 * @param  int $post_id Post ID.
	 * @return int          Term ID.
	 */
	public static function get_term_from_post( $post_id ) {
		$term_id = get_post_meta( $post_id, '_linked_term_id', true );
		return absint( $term_id );
	}

	/**
	 * Static helper to get the post_id from a term.
	 *
	 * @param  int $term_id Term ID.
	 * @return int          Post ID.
	 */
	public static function get_post_from_term( $term_id ) {
		$post_id = get_term_meta( $term_id, '_linked_post_id', true );
		return absint( $post_id );
	}

	/**
	 * Create linked posts for the first time.
	 */
	public function create_posts() {

		$option_key = 'created_term_post_link_' . $this->taxonomy . '_' . $this->post_type;

		// Get the option to see if posts have already been created.
		if ( empty( get_option( $option_key ) ) ) {

			// Select all terms without a linked post id.
			$term_query = new \WP_Term_Query(
				[
					'taxonomy'   => $this->taxonomy,
					'orderby'    => 'parent',
					'hide_empty' => false,
					'meta_query' => [
						[
							'key'     => '_linked_post_id',
							'compare' => 'NOT EXISTS',
						],
					],
				]
			);

			// Loop and create post for each term.
			if ( ! empty( $term_query->terms ) ) {
				foreach ( $term_query->terms as $term ) {
					$this->create_post( $term->term_id );
				}
			} else {
				// Create option to indicate this process has completed.
				add_option( $option_key, true );
			}
		}
	}

	/**
	 * Create a post for a given term_id.
	 *
	 * @param int $term_id ID of the term.
	 */
	public function create_post( $term_id ) {

		// Get term object.
		$term = get_term( $term_id );

		// Validate.
		if ( $term instanceof \WP_Term ) {

			// Determine post parent ID.
			$parent_linked_post_id = 0;
			if ( 0 !== $term->parent ) {
				$parent_linked_post_id = self::get_post_from_term( $term->parent );
			}

			// Populate WP SEO fields.
			$wp_seo = get_option( "wp-seo-term-{$term->term_taxonomy_id}" );
			$wp_seo = wp_parse_args(
				$wp_seo,
				[
					'_meta_title'       => '',
					'_meta_description' => '',
					'_meta_keywords'    => '',
				]
			);

			// Insert post.
			$post_id = wp_insert_post(
				[
					'post_title'   => $term->name,
					'post_name'    => $term->slug,
					'post_type'    => $this->post_type,
					'post_content' => $term->description,
					'post_status'  => 'publish',
					'post_parent'  => $parent_linked_post_id,
					'meta_input'   => [
						'_linked_term_id'   => $term_id,
						'_meta_title'       => $wp_seo['title'] ?? '',
						'_meta_description' => $wp_seo['description'] ?? '',
						'_meta_keywords'    => $wp_seo['keywords'] ?? '',
					],
				],
				true
			);

			// Validate and save in term meta.
			if ( ! is_wp_error( $post_id ) ) {
				update_term_meta( $term_id, '_linked_post_id', $post_id );
			}
		}
	}

	/**
	 * On linked post save, update the linked term.
	 *
	 * @param int $post_id Post ID being updated.
	 */
	public function save_post( $post_id ) {
		if ( get_post_type( $post_id ) !== $this->post_type ) {
			return;
		}

		// Get vars.
		$post           = get_post( $post_id );
		$term_id        = $this->get_term_from_post( $post_id );
		$parent_term_id = $this->get_term_from_post( $post->post_parent );

		// Update term whenever the post is updated.
		wp_update_term(
			$term_id,
			$this->taxonomy,
			[
				'name'   => $post->post_title,
				'slug'   => $post->post_name,
				'parent' => $parent_term_id,
			]
		);

		// Save WP SEO values in an option where it expects.
		$term = get_term( $term_id );
		if ( $term instanceof \WP_Term ) {

			// Check that it's a valid WP SEO taxonomy.
			$settings = wp_seo_settings();
			if ( in_array( $term->taxonomy, array_keys( $settings->get_taxonomies() ), true ) ) {

				// Build option.
				$option_value = [
					'title'       => get_post_meta( $post_id, '_meta_title', true ),
					'description' => get_post_meta( $post_id, '_meta_description', true ),
					'keywords'    => get_post_meta( $post_id, '_meta_keywords', true ),
				];
				update_option( "wp-seo-term-{$term->term_taxonomy_id}", $option_value, false );
			}
		}
	}

	/**
	 * Delete a linked post when the term is deleted.
	 *
	 * @param int $term_id Term ID.
	 */
	public function delete_post( $term_id ) {

		// Get all posts associated with the deleted term.
		$posts = get_posts(
			[
				'posts_per_page' => 1,
				'post_type'      => $this->post_type,
				'meta_key'       => '_linked_term_id',
				'meta_value'     => $term_id,
			]
		);

		// Delete all posts.
		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				wp_delete_post( $post->ID );
			}
		}
	}

	/**
	 * Setup various redirects so that the editor always uses the post version.
	 */
	public function admin_screen_redirects() {

		$screen = get_current_screen();

		$force_access = false;
		if ( isset( $_GET['force-access'] ) ) {
			$force_access = (bool) $_GET['force-access'];
		}

		/**
		 * Term edit screen.
		 */
		if (
			isset( $screen->base )
			&& 'term' === $screen->base
			&& isset( $screen->taxonomy )
			&& $this->taxonomy === $screen->taxonomy
			&& isset( $_GET['tag_ID'] )
			&& true !== $force_access
		) {
			// Get term_id from query vars.
			$term_id = absint( $_GET['tag_ID'] );

			// Get the linked post.
			$linked_post_id = $this->get_post_from_term( $term_id );

			// Redirect to post.
			if ( ! empty( $linked_post_id ) ) {
				wp_redirect( admin_url( "post.php?post={$linked_post_id}&action=edit" ) );
				exit();
			}
		}

		/**
		 * Post type list.
		 */
		if (
			isset( $screen->base )
			&& 'edit' === $screen->base
			&& isset( $screen->post_type )
			&& $this->post_type === $screen->post_type
			&& true !== $force_access
		) {
			// Redirect to taxonomy tags.
			wp_redirect( admin_url( 'edit-tags.php?taxonomy=' . $this->taxonomy ) );
			exit();
		}

		/**
		 * Post type add.
		 */
		if (
			isset( $screen->action )
			&& 'add' === $screen->action
			&& isset( $screen->base )
			&& 'post' === $screen->base
			&& isset( $screen->post_type )
			&& $this->post_type === $screen->post_type
			&& true !== $force_access
		) {
			// Redirect to taxonomy tags.
			wp_redirect( admin_url( 'edit-tags.php?taxonomy=' . $this->taxonomy ) );
			exit();
		}
	}

	/**
	 * Modify the taxonomy row actions.
	 *
	 * @param  array    $actions Original row actions.
	 * @param  \WP_Term $term    Term object.
	 * @return array             Updated row actions.
	 */
	public function term_row_actions( $actions, $term ) {

		// Get linked post id and edit link.
		$linked_post_id        = get_term_meta( $term->term_id, '_linked_post_id', true );
		$linked_post_edit_link = get_edit_post_link( $linked_post_id );

		// We have a linked post to use.
		if ( ! empty( $linked_post_edit_link ) ) {

			// Build new action.
			$new_actions = [
				'edit_linked_post' => sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_url( $linked_post_edit_link ),
					esc_html__( 'Edit', 'cpr' )
				),
			];

			// Merge with existing actions.
			$actions = array_merge( $new_actions, $actions );

			// Remove default edit screen link.
			if ( isset( $actions['edit'] ) ) {
				unset( $actions['edit'] );
			}

			// Remove quick edit link.
			if ( isset( $actions['inline hide-if-no-js'] ) ) {
				unset( $actions['inline hide-if-no-js'] );
			}
		}

		return $actions;
	}

	/**
	 * Route any attempts at getting term meta through the linked post when
	 * needed.
	 *
	 * @param  mixed  $value    Meta value.
	 * @param  int    $term_id  Term ID.
	 * @param  string $meta_key Meta key.
	 * @param  bool   $single   Only return the first value.
	 * @return mixed            Meta value.
	 */
	public function get_term_metadata( $value, $term_id, $meta_key, $single ) {

		// Get term.
		$term = get_term( $term_id );

		// Ensure the query is for a taxonomy we want to filter.
		if ( ! in_array( $term->taxonomy, self::$taxonomies, true ) ) {
			return $value;
		}

		// Remove filter to avoid infinite loop and get the $post_id.
		remove_filter( 'get_term_metadata', [ $this, 'get_term_metadata' ], 10, 4 );
		$post_id = get_term_meta( $term_id, '_linked_post_id', true );
		add_filter( 'get_term_metadata', [ $this, 'get_term_metadata' ], 10, 4 );

		// Allow us to access the post content directly as if it were term meta data.
		if ( 'post_content' === $meta_key ) {
			$value = get_post_field( 'post_content', $post_id );
		} elseif ( metadata_exists( 'post', $post_id, $meta_key ) ) {
			$value = get_post_meta( $post_id, $meta_key, false );
		}

		return $value;
	}

	/**
	 * Modify the post link permalink to use term permalink.
	 *
	 * @param  string   $permalink Original permalink.
	 * @param  \WP_Post $post      WP_Post object of this post.
	 * @return string              Modified permalink.
	 */
	public function modify_permalink( $permalink, $post ) {
		if ( $this->post_type === $post->post_type ) {

			// Get term id and link from post meta.
			$term_id   = $this->get_term_from_post( $post->ID );
			$term_link = get_term_link( $term_id );

			// Return term link.
			if ( ! is_wp_error( $term_link ) ) {
				return $term_link;
			}
		}
		return $permalink;
	}

	/**
	 * Modify term admin columns.
	 *
	 * @param  array $columns Array of columns.
	 * @return array          Updated array.
	 */
	public function modify_columns( $columns ) {
		if ( isset( $columns['description'] ) ) {
			unset( $columns['description'] );
		}

		return $columns;
	}
}

/**
 * Create a new link between a taxonomy and a post type.
 *
 * @param  string $taxonomy  Taxonomy to link.
 * @param  string $post_type Post type to link.
 */
function create_term_post_link( $taxonomy, $post_type ) {
	new Term_Post_Link( $taxonomy, $post_type );
}
