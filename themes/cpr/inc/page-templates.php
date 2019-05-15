<?php
/**
 * Page template registration.
 *
 * @package CPR
 */

namespace CPR;

// Setup Grid Group page template.
add_action( 'after_setup_theme', [ '\\CPR\\Components\\Templates\\Grid_Group_Page', 'register_page_template' ] );
