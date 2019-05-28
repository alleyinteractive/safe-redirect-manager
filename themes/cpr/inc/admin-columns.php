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
					'<a href="%1$s">Website</a>',
					esc_url( get_post_meta( $post_id, 'link', true ) )
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
