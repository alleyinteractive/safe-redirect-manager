<?php
/**
 * Actions and filters that don't really have a good home.
 *
 * @package CPR
 */

namespace CPR;

/**
 * When a show episode's `show_segment_ids` meta data changes, determine which
 * segments have been added/removed, and adjust each segment's
 * `_show_episode_id` key. This key is how segments know what their parent
 * episode is. To edit or modify this key outside the scope of this function
 * _will_ cause problems.
 *
 * @param int    $meta_id    Meta ID.
 * @param int    $object_id  Object ID.
 * @param string $meta_key   Meta key.
 * @param string $meta_value Meta value.
 */
function update_show_episode_segments_connection( $meta_id, $object_id, $meta_key, $meta_value ) {

	// We only care about this key.
	if ( 'show_segment_ids' !== $meta_key ) {
		return;

	}

	// The (new) real segment ids.
	$new_segment_ids = array_filter( (array) $meta_value );

	$existing_segment_ids = ( new \WP_Query(
		[
			'fields'         => 'ids',
			'meta_key'       => '_show_episode_id',
			'meta_value'     => $object_id,
			'post_status'    => 'any',
			'post_type'      => 'show-segment',
			'posts_per_page' => 25,
		]
	) )->posts ?? [];

	// Determine the overlap between new ids and existing ids. These need no
	// action, but will be used to compare against what remains in the new and
	// existing arrays. This allows us to quickly determine which ids are new
	// and need to be created, which are old and need to be removed, and which
	// can nbe left along.
	$segment_ids_to_leave_alone = array_intersect( $new_segment_ids, $existing_segment_ids );

	// Loop through new ids that aren't arleady set.
	array_map(
		function( $segment_id_to_add ) use ( $object_id ) {
			update_post_meta( $segment_id_to_add, '_show_episode_id', $object_id );
		},
		array_diff( $new_segment_ids, $segment_ids_to_leave_alone )
	);

	// Loop through old ids that have been removed.
	array_map(
		function( $segment_id_to_remove ) use ( $object_id ) {
			delete_post_meta( $segment_id_to_remove, '_show_episode_id' );
		},
		array_diff( $existing_segment_ids, $segment_ids_to_leave_alone )
	);
}
add_action( 'updated_post_meta', __NAMESPACE__ . '\update_show_episode_segments_connection', 10, 4 );
add_action( 'added_post_meta', __NAMESPACE__ . '\update_show_episode_segments_connection', 10, 4 );
