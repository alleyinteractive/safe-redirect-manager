<?php
/**
 * CPR functions and definitions.
 *
 * @package CPR
 */

namespace CPR;

define( 'CPR_PATH', dirname( __FILE__ ) );
define( 'CPR_URL', get_template_directory_uri() );
define( 'CPR_TRANSCODING_TOKEN', '{Rr+C+8vET/r4Nky476kW6bv' );
define( 'CPR_TRANSCODING_NOT_STARTED', 0 );
define( 'CPR_TRANSCODING_PROCESSING', 1 );
define( 'CPR_TRANSCODING_SUCCESS', 2 );
define( 'CPR_TRANSCODING_ERROR', 3 );

// Handle local dev port issues.
if ( false !== strpos( site_url(), 'alley' ) ) {
	remove_filter( 'template_redirect', 'redirect_canonical' );
}

/**
 * Alleypack modules.
 */
\Alleypack\load_module( 'block-converter', '1.0' );
\Alleypack\load_module( 'cli-helpers', '1.1' );
\Alleypack\load_module( 'fm-helpers', '1.0' );
\Alleypack\load_module( 'fm-modules', '1.0' );
\Alleypack\load_module( 'page-templates', '1.0' );
\Alleypack\load_module( 'path-dispatch', '1.0' );
\Alleypack\load_module( 'landing-pages', '1.0' );
\Alleypack\load_module( 'singleton', '1.0' );
\Alleypack\load_module( 'term-post-link', '1.0' );
\Alleypack\load_module( 'unique-wp-query', '1.0' );
\Alleypack\load_module( 'media-fields', '1.0' );

// Create Term Post Link for Newsletter.
\Alleypack\create_term_post_link( 'newsletter', 'newsletter-post' );

// WordPress utilities.
require_once CPR_PATH . '/inc/class-wp-utils.php';

// Activate and customize plugins.
require_once CPR_PATH . '/inc/plugins.php';

// Admin customizations.
if ( is_admin() ) {
	require_once CPR_PATH . '/inc/admin.php';
	require_once CPR_PATH . '/inc/admin-columns.php';
}

// wp-cli command.
if ( WP_Utils::wp_cli() ) {
	require_once CPR_PATH . '/inc/cli.php';
}

// Migration.
require_once CPR_PATH . '/migration/class-migration.php';

// Traits.
require_once CPR_PATH . '/inc/traits/trait-backfill.php';
require_once CPR_PATH . '/inc/traits/trait-event.php';
require_once CPR_PATH . '/inc/traits/trait-wp-post.php';

// Data endpoints.
require_once CPR_PATH . '/data/class-underwriters.php';

// Ad integrations.
require_once CPR_PATH . '/inc/ads.php';

// Ajax.
require_once CPR_PATH . '/inc/ajax.php';

// Include classes used to integrate with external APIs.
require_once CPR_PATH . '/inc/api.php';

// Manage static assets (js and css).
require_once CPR_PATH . '/inc/assets.php';

// Attachment.
require_once CPR_PATH . '/inc/attachment.php';

// Authors.
require_once CPR_PATH . '/inc/authors.php';

// Cache.
require_once CPR_PATH . '/inc/cache.php';

// This site's RSS, Atom, JSON, etc. feeds.
require_once CPR_PATH . '/inc/feeds.php';

// Gutenberg.
require_once CPR_PATH . '/inc/gutenberg.php';

// Terms Helpers.
require_once CPR_PATH . '/inc/helpers/terms.php';

// WP_Query Helpers.
require_once CPR_PATH . '/inc/helpers/wp-query.php';

// Actions and filters that don't really have a good home.
require_once CPR_PATH . '/inc/hooks.php';

// Setup landing pages.
require_once CPR_PATH . '/inc/landing-pages.php';

// Media includes.
require_once CPR_PATH . '/inc/media.php';

// Page Templates.
require_once CPR_PATH . '/inc/page-templates.php';

// Permalink.
require_once CPR_PATH . '/inc/permalink.php';

// Previews.
require_once CPR_PATH . '/inc/previews.php';

// Query modifications and manipulations.
require_once CPR_PATH . '/inc/query.php';

// Rewrites.
require_once CPR_PATH . '/inc/rewrites.php';

// Routing.
require_once CPR_PATH . '/inc/routing.php';

// Form Endpoints.
require_once CPR_PATH . '/inc/forms.php';

// Search.
require_once CPR_PATH . '/inc/search.php';

// SEO.
require_once CPR_PATH . '/inc/seo.php';

// Sidebars.
require_once CPR_PATH . '/inc/sidebars.php';

// Helpers.
require_once CPR_PATH . '/inc/template-tags.php';

// Theme setup.
require_once CPR_PATH . '/inc/theme.php';

// Users.
require_once CPR_PATH . '/inc/users.php';

// Loader for partials.
require_once CPR_PATH . '/inc/partials/partials.php';

/**
 * Widgets.
 */
require_once CPR_PATH . '/inc/widgets/class-events-widget.php';
require_once CPR_PATH . '/inc/widgets/class-external-link-widget.php';
require_once CPR_PATH . '/inc/widgets/class-colorado-wonders-widget.php';
require_once CPR_PATH . '/inc/widgets/class-live-stream-widget.php';

// Content types and taxonomies should be included below. In order to scaffold
// them, leave the Begin and End comments in place.
/* Begin Data Structures */

// Fieldmanager Fields.
require_once CPR_PATH . '/inc/fields.php';

// Post Type Base Class.
require_once CPR_PATH . '/inc/post-types/class-cpr-post-type.php';

// Taxonomy Base Class.
require_once CPR_PATH . '/inc/taxonomies/class-cpr-taxonomy.php';

// Podcasts Taxonomy (tax:podcast).
require_once CPR_PATH . '/inc/taxonomies/class-cpr-taxonomy-podcast.php';

// Sections Taxonomy (tax:section).
require_once CPR_PATH . '/inc/taxonomies/class-cpr-taxonomy-section.php';

// Podcast Posts Post Type (cpt:podcast-post).
require_once CPR_PATH . '/inc/post-types/class-cpr-post-type-podcast-post.php';

// Podcast Episodes Post Type (cpt:podcast-episode).
require_once CPR_PATH . '/inc/post-types/class-cpr-post-type-podcast-episode.php';

// Top 30s Post Type (cpt:top-30).
require_once CPR_PATH . '/inc/post-types/class-cpr-post-type-top-30.php';

// Albums Post Type (cpt:album).
require_once CPR_PATH . '/inc/post-types/class-cpr-post-type-album.php';

// Labels Taxonomy (tax:label).
require_once CPR_PATH . '/inc/taxonomies/class-cpr-taxonomy-label.php';

// Artists Taxonomy (tax:artist).
require_once CPR_PATH . '/inc/taxonomies/class-cpr-taxonomy-artist.php';

// Underwriters Post Type (cpt:underwriter).
require_once CPR_PATH . '/inc/post-types/class-cpr-post-type-underwriter.php';

// Jobs Post Type (cpt:job).
require_once CPR_PATH . '/inc/post-types/class-cpr-post-type-job.php';

// External Links Post Type (cpt:external-link).
require_once CPR_PATH . '/inc/post-types/class-cpr-post-type-external-link.php';

// Alerts Post Type (cpt:alert).
require_once CPR_PATH . '/inc/post-types/class-cpr-post-type-alert.php';

// Show Segments Post Type (cpt:show-segment).
require_once CPR_PATH . '/inc/post-types/class-cpr-post-type-show-segment.php';

// Show Episodes Post Type (cpt:show-episode).
require_once CPR_PATH . '/inc/post-types/class-cpr-post-type-show-episode.php';

// Shows Taxonomy (tax:show).
require_once CPR_PATH . '/inc/taxonomies/class-cpr-taxonomy-show.php';

// Show Posts Post Type (cpt:show-post).
require_once CPR_PATH . '/inc/post-types/class-cpr-post-type-show-post.php';

// Press Releases Post Type (cpt:press-release).
require_once CPR_PATH . '/inc/post-types/class-cpr-post-type-press-release.php';

// Underwriter Categories Taxonomy (tax:underwriter-category).
require_once CPR_PATH . '/inc/taxonomies/class-cpr-taxonomy-underwriter-category.php';

// Newsletter Singles Post Type (cpt:newsletter-single).
require_once CPR_PATH . '/inc/post-types/class-cpr-post-type-newsletter-single.php';

// Newsletter Posts Post Type (cpt:newsletter-post).
require_once CPR_PATH . '/inc/post-types/class-cpr-post-type-newsletter-post.php';

// Newsletters Taxonomy (tax:newsletter).
require_once CPR_PATH . '/inc/taxonomies/class-cpr-taxonomy-newsletter.php';

/* End Data Structures */
