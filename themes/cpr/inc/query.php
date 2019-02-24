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
add_action( 'pre_get_posts', [ '\\CPR\\Components\\Templates\\News', 'pre_get_posts' ] );
