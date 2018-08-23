<?php
/**
 * CPR functions and definitions.
 *
 * @package Cpr
 */

namespace Cpr;

define( 'CPR_PATH', dirname( __FILE__ ) );
define( 'CPR_URL', get_template_directory_uri() );

// WordPress utilities.
require_once CPR_PATH . '/inc/class-wp-utils.php';

// Activate and customize plugins.
require_once CPR_PATH . '/inc/plugins.php';

// Admin customizations.
if ( is_admin() ) {
	require_once CPR_PATH . '/inc/admin.php';
}

// wp-cli command.
if ( WP_Utils::wp_cli() ) {
	require_once CPR_PATH . '/inc/cli.php';
}

// Ad integrations.
require_once CPR_PATH . '/inc/ads.php';

// Ajax.
require_once CPR_PATH . '/inc/ajax.php';

// Include classes used to integrate with external APIs.
require_once CPR_PATH . '/inc/api.php';

// Manage static assets (js and css).
require_once CPR_PATH . '/inc/assets.php';

// Authors.
require_once CPR_PATH . '/inc/authors.php';

// Cache.
require_once CPR_PATH . '/inc/cache.php';

// Include comments.
require_once CPR_PATH . '/inc/comments.php';

// Customizer additions.
require_once CPR_PATH . '/inc/customizer.php';

// This site's RSS, Atom, JSON, etc. feeds.
require_once CPR_PATH . '/inc/feeds.php';

// Media includes.
require_once CPR_PATH . '/inc/media.php';

// Navigation & Menus.
require_once CPR_PATH . '/inc/nav.php';

// Query modifications and manipulations.
require_once CPR_PATH . '/inc/query.php';

// Rewrites.
require_once CPR_PATH . '/inc/rewrites.php';

// Search.
require_once CPR_PATH . '/inc/search.php';

// Shortcodes.
require_once CPR_PATH . '/inc/shortcodes.php';

// Include sidebars and widgets.
require_once CPR_PATH . '/inc/sidebars.php';

// Helpers.
require_once CPR_PATH . '/inc/template-tags.php';

// Theme setup.
require_once CPR_PATH . '/inc/theme.php';

// Users.
require_once CPR_PATH . '/inc/users.php';

// Zoninator zones/customizations.
require_once CPR_PATH . '/inc/zones.php';

// Loader for partials.
require_once CPR_PATH . '/inc/partials/partials.php';

// Template loader.
require_once CPR_PATH . '/inc/class-wrapping.php';

// Content types and taxonomies should be included below. In order to scaffold
// them, leave the Begin and End comments in place.
/* Begin Data Structures */

/* End Data Structures */
