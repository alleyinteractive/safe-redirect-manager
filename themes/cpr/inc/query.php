<?php
/**
 * One-off query modifications and manipulations (e.g. through pre_get_posts).
 * Modifications tied to a larger features should reside with the rest of the
 * code for that feature.
 *
 * @package CPR
 */

namespace CPR;

// Modfiy search results.
add_action( 'pre_get_posts', [ '\\CPR\\Components\\Templates\\Search', 'pre_get_posts' ] );

// Modify term archive results.
add_action( 'pre_get_posts', [ '\\CPR\\Components\\Templates\\Term_Archive', 'pre_get_posts' ] );

// Modify author archive results.
add_action( 'pre_get_posts', [ '\\CPR\\Components\\Templates\\Author_Archive', 'pre_get_posts' ] );

// Get all underwriters
add_action( 'pre_get_posts', [ '\\CPR\\Components\\Templates\\Underwriter_Archive', 'pre_get_posts' ] );
