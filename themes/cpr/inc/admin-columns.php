<?php
/**
 * Post list view customizations to admin columns.
 *
 * @package CPR
 */

namespace CPR;

/**
 * Add columns to the underwriter post type.
 *
 * @param array $columns An array of columns to render.
 * @return array
 */
function add_custom_underwriter_columns( array $columns ) : array {

	// The easiest way to build this array is by looping through the existing
	// one, and adding our custom values where appropriate. This is because
	// this hook relies on the order of the array values, even though it's an
	// associative array, where you'd assume it wouldn't matter.
	$new_columns = [];

	// Loop through each column.
	foreach ( $columns as $key => $value ) {

		// Insert new columns.
		switch ( $key ) {
			case 'date':
				$new_columns['about'] = __( 'About', 'cpr' );
				break;
		}

		// Reinsert the key.
		$new_columns[ $key ] = $value;
	}

	return $new_columns;
}
add_filter( 'manage_underwriter_posts_columns', __NAMESPACE__ . '\add_custom_underwriter_columns' );

/**
 * Populate the custom underwriter columns.
 *
 * @param string $column  Column value.
 * @param int    $post_id Applicable post ID.
 */
function populate_custom_underwriter_columns( string $column, int $post_id ) {
	switch ( $column ) {
		case 'about':
			// Setup an array to build the table.
			$fields = [
				__( 'Corporate Partner', 'cpr' ) => absint( get_post_meta( $post_id, 'is_corporate_partner', true ) ) ? 'Yes' : '',
				__( 'Website:', 'cpr' )          => sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_url( get_post_meta( $post_id, 'link', true ) ),
					esc_html__( 'Website', 'cpr' )
				),
				__( 'Phone:', 'cpr' )            => get_post_meta( $post_id, 'phone_number', true ),
				__( 'Address:', 'cpr' )          => wpautop( (string) get_post_meta( $post_id, 'address', true ) ),
			];

			// Output a table.
			echo '<table>';
			foreach ( $fields as $label => $output ) {
				if ( ! empty( $output ) ) {
					echo '<tr>';
					printf(
						'<td><strong>%1$s</strong></td>',
						esc_html( $label )
					);
					echo '<td>' . wp_kses_post( $output ) . '</td>';
					echo '</tr>';
				}
			}
			echo '</table>';
			break;
	}
}
add_action( 'manage_underwriter_posts_custom_column', __NAMESPACE__ . '\populate_custom_underwriter_columns', 10, 2 );


/**
 * Add columns to the show segment post type.
 *
 * @param array $columns An array of columns to render.
 * @return array
 */
function add_custom_show_segment_columns( array $columns ) : array {

	// The easiest way to build this array is by looping through the existing
	// one, and adding our custom values where appropriate. This is because
	// this hook relies on the order of the array values, even though it's an
	// associative array, where you'd assume it wouldn't matter.
	$new_columns = [];

	// Loop through each column.
	foreach ( $columns as $key => $value ) {

		// Insert new columns.
		switch ( $key ) {
			case 'date':
				$new_columns['show']    = __( 'Show', 'cpr' );
				$new_columns['episode'] = __( 'Episode', 'cpr' );
				break;
		}

		// Reinsert the key.
		$new_columns[ $key ] = $value;
	}

	return $new_columns;
}
add_filter( 'manage_show-segment_posts_columns', __NAMESPACE__ . '\add_custom_show_segment_columns' );

/**
 * Populate the custom show segment columns.
 *
 * @param string $column  Column value.
 * @param int    $post_id Applicable post ID.
 */
function populate_custom_show_segment_columns( string $column, int $post_id ) {
	switch ( $column ) {
		/**
		 * Display the episode title + link.
		 */
		case 'episode':

			// Get and validate show episode id.
			$show_episode_id = absint( get_post_meta( $post_id, '_show_episode_id', true ) );
			if ( 0 === $show_episode_id || ! get_post( $show_episode_id ) instanceof \WP_Post ) {
				break;
			}

			printf(
				'<a href="%2$s">%1$s</a>',
				esc_html( get_the_title( $show_episode_id ) ),
				esc_url( get_edit_post_link( $show_episode_id ) )
			);
			break;

		/**
		 * Display the show title + link.
		 */
		case 'show':

			// Get and validate show episode id.
			$show_episode_id = absint( get_post_meta( $post_id, '_show_episode_id', true ) );
			if ( ! get_post( $show_episode_id ) instanceof \WP_Post ) {
				break;
			}

			// Get and validate the show term.
			$show_terms = wp_get_object_terms( $show_episode_id, 'show' );
			if ( ! isset( $show_terms[0] ) || ! $show_terms[0] instanceof \WP_Term ) {
				break;
			}

			$show_post = \Alleypack\Term_Post_Link::get_post_from_term( $show_terms[0]->term_id );

			printf(
				'<a href="%2$s">%1$s</a>',
				esc_html( get_the_title( $show_post) ),
				esc_url( get_edit_post_link( $show_post ) )
			);
			break;
	}
}
add_action( 'manage_show-segment_posts_custom_column', __NAMESPACE__ . '\populate_custom_show_segment_columns', 10, 2 );
