<?php
/**
 * Plugin Name: CPR MU Loader
 * Description: Wrapper plugin to manually require non-mu compatible plugins
 * Author: Alley Interactive
 * Version: 1.0
 */

// CPR is blog #1.
if ( 1 !== get_current_blog_id() ) {
	return;
}

$plugins = [
	'/alleypack/alleypack.php',
	'/amp/amp.php',
	'/archiveless/archiveless.php',
	'/co-authors-plus/co-authors-plus.php',
	'/fm-zones/fm-zones.php',
	'/safe-redirect-manager/safe-redirect-manager.php',
	'/the-events-calendar/the-events-calendar.php',
	'/wordpress-fieldmanager/fieldmanager.php',
	'/wp-components/wp-components.php',
	'/wp-irving/wp-irving.php',
	'/wp-redis/wp-redis.php',
	// '/wp-seo/wp-seo.php',
	'/wpcom-thumbnail-editor/wpcom-thumbnail-editor.php',
	'/wpcom-legacy-redirector/wpcom-legacy-redirector.php',
	'/fm-widgets/fm-widgets.php', // Depends on FM.
];

// Begin the process of loading the MU Plugins.
if ( is_array( $plugins ) ) {
	foreach ( $plugins as $plugin_name ) {
		if ( file_exists( WPMU_PLUGIN_DIR . $plugin_name ) ) {
			// Require if the file is found.
			require_once WPMU_PLUGIN_DIR . $plugin_name;
		} else {
			// Or display an admin notice.
			add_action( 'admin_notices', function() use ( $plugin_name ) {
				echo '<div class="notice notice-error"><p>';
				printf( __( 'Could not load the MU-Plugin located in /mu-plugins%1$s', 'load-mu-plugins' ), $plugin_name );
				echo '</p></div>';
			} );
		}
	}
}

/**
 * Use Jetpack as ES WP Query adapter.
 */
add_action(
	'after_setup_theme',
	function () {
		if ( function_exists( 'es_wp_query_load_adapter' ) ) {
			es_wp_query_load_adapter( 'jetpack-search' );
		}
	}
);
