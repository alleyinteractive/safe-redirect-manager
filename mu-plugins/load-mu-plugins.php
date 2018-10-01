<?php
/**
 * Plugin Name: MU Loader
 * Description: Wrapper plugin to manually require non-mu compatible plugins
 * Author: Alley Interactive
 * Version: 1.0
 */

$plugins = [
	'/ad-layers/ad-layers.php',
	'/alleypack/alleypack.php',
	'/amp-wp/amp.php',
	'/co-authors-plus/co-authors-plus.php',
	'/edit-flow/edit_flow.php',
	'/fm-zones/fm-zones.php',
	'/msm-sitemap/msm-sitemap.php',
	'/safe-redirect-manager/safe-redirect-manager.php',
	'/wordpress-fieldmanager/fieldmanager.php',
	'/wp-irving/wp-irving.php',
	'/wp-redis/wp-redis.php',
	'/wp-seo/wp-seo.php',
	'/wpcom-thumbnail-editor/wpcom-thumbnail-editor.php',
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
