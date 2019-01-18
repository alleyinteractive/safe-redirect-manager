<?php
/**
 * CPR functions and definitions.
 *
 * @package CPR
 */

namespace CPR;

define( 'CPR_PATH', dirname( __FILE__ ) );
define( 'CPR_URL', get_template_directory_uri() );

/**
 * Aleypack modules.
 */
\Alleypack\load_module( 'term-post-link', '1.0' );
\Alleypack\load_module( 'wp-components', '1.0' );

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

// WP Irving.
// @todo consolidate file locations.
if ( defined( 'WP_IRVING_VERSION' ) && WP_IRVING_VERSION ) {

	// Components.
	require_once CPR_PATH . '/components/global/donate-button/class-donate-button.php';
	require_once CPR_PATH . '/components/global/footer/class-footer.php';
	require_once CPR_PATH . '/components/global/slim-navigation/class-slim-navigation.php';

	// Irving Templates.
	require_once CPR_PATH . '/templates/class-article.php';
	require_once CPR_PATH . '/templates/class-episode.php';
	require_once CPR_PATH . '/templates/class-error.php';
	require_once CPR_PATH . '/templates/class-landing-page.php';
	require_once CPR_PATH . '/templates/class-page.php';
	require_once CPR_PATH . '/templates/class-search.php';
	require_once CPR_PATH . '/templates/class-term.php';
	require_once CPR_PATH . '/templates/class-term.php';
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

// This site's RSS, Atom, JSON, etc. feeds.
require_once CPR_PATH . '/inc/feeds.php';

// Helpers.
require_once CPR_PATH . '/inc/helpers/terms.php';

// Setup landing pages.
require_once CPR_PATH . '/inc/landing-pages.php';

// Media includes.
require_once CPR_PATH . '/inc/media.php';

// Query modifications and manipulations.
require_once CPR_PATH . '/inc/query.php';

// Rewrites.
require_once CPR_PATH . '/inc/rewrites.php';

// Routing.
require_once CPR_PATH . '/inc/routing.php';

// Search.
require_once CPR_PATH . '/inc/search.php';

// SEO.
require_once CPR_PATH . '/inc/seo.php';

// Helpers.
require_once CPR_PATH . '/inc/template-tags.php';

// Theme setup.
require_once CPR_PATH . '/inc/theme.php';

// Users.
require_once CPR_PATH . '/inc/users.php';

// Loader for partials.
require_once CPR_PATH . '/inc/partials/partials.php';

// Landing pages.
require_once CPR_PATH . '/inc/class-landing-pages.php';

// Content types and taxonomies should be included below. In order to scaffold
// them, leave the Begin and End comments in place.
/* Begin Data Structures */

// Fieldmanager Fields.
require_once CPR_PATH . '/inc/fields.php';

// Post Type Base Class.
require_once CPR_PATH . '/inc/post-types/class-cpr-post-type.php';

// Taxonomy Base Class.
require_once CPR_PATH . '/inc/taxonomies/class-cpr-taxonomy.php';

// Episodes Post Type (cpt:episode).
require_once CPR_PATH . '/inc/post-types/class-cpr-post-type-episode.php';

// Podcasts Taxonomy (tax:podcast).
require_once CPR_PATH . '/inc/taxonomies/class-cpr-taxonomy-podcast.php';

// Sections Taxonomy (tax:section).
require_once CPR_PATH . '/inc/taxonomies/class-cpr-taxonomy-section.php';

// Podcast Posts Post Type (cpt:podcast-post).
require_once CPR_PATH . '/inc/post-types/class-cpr-post-type-podcast-post.php';

/* End Data Structures */
