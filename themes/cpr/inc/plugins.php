<?php
/**
 * Load and customize plugins
 *
 * @package CPR
 */

namespace CPR;

// Ensure the basic Google Maps API is always used.
add_filter( 'tribe_is_using_basic_gmaps_api', '__return_true' );
