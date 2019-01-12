<?php
/**
 * Manage static assets.
 *
 * @package CPR
 */

namespace CPR;

// @codingStandardsIgnoreFile WordPress.VIP.RestrictedFunctions.cookies_setcookie WordPress.VIP.RestrictedVariables.cache_constraints___COOKIE

/**
 * Add custom query var for webpack hot-reloading.
 *
 * @param array $vars Array of current query vars.
 * @return array $vars Array of query vars.
 */
function webpack_query_vars( $vars ) {
	// Add a query var to enable hot reloading.
	$vars[] = 'fe-dev';

	return $vars;
}
add_filter( 'query_vars', __NAMESPACE__ . '\webpack_query_vars' );

/**
 * Get the version for a given asset.
 *
 * @param string $asset_path Entry point and asset type separated by a '.'.
 * @return string The asset version.
 */
function ai_get_versioned_asset( $asset_path ) {
	static $asset_map;

	if ( ! isset( $asset_map ) ) {
		$asset_map_file = CPR_PATH . '/client/build/assetMap.json';

		if ( file_exists( $asset_map_file ) && 0 === validate_file( $asset_map_file ) ) {
			ob_start();
			include $asset_map_file;
			$asset_map = json_decode( ob_get_clean(), true );
		} else {
			$asset_map = [];
		}
	}

	/*
	 * Appending a '.' ensures the explode() doesn't generate a notice while
	 * allowing the variable names to be more readable via list().
	 */
	list( $entrypoint, $type ) = explode( '.', "$asset_path." );

	return isset( $asset_map[ $entrypoint ][ $type ] ) ? $asset_map[ $entrypoint ][ $type ] : '';
}

/**
 * Enqueues scripts and styles for the frontend
 */
function enqueue_assets() {
	// Dev-specific scripts.
	$toggle_fe_dev_mode = get_query_var( 'fe-dev' );
	if ( 'off' === $toggle_fe_dev_mode ) {
		setcookie( 'fe-dev', '0', 0, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
		$_COOKIE['fe-dev'] = null;
	}

	if ( 'on' === $toggle_fe_dev_mode || ! empty( $_COOKIE['fe-dev'] ) ) {
		wp_enqueue_script( // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NoExplicitVersion
			'dev',
			'//localhost:8080/client/build/js/dev.bundle.js',
			array(),
			false,
			false
		);
		setcookie( 'fe-dev', '1', 0, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
	} else {
		wp_enqueue_script( 'cpr-common-js', get_template_directory_uri() . '/client/build/' . ai_get_versioned_asset( 'common.js' ), array( 'jquery' ), '1.0' );
		wp_enqueue_style( 'cpr-common-js', get_template_directory_uri() . '/client/build/' . ai_get_versioned_asset( 'common.css' ), array(), '1.0' );

		if ( is_home() ) {
			wp_enqueue_style( 'cpr-home', get_template_directory_uri() . '/client/build/' . ai_get_versioned_asset( 'home.css' ), array(), '1.0' );
			wp_enqueue_script( 'cpr-home-js', get_template_directory_uri() . '/client/build/' . ai_get_versioned_asset( 'home.js' ), array( 'cpr-common-js' ), '1.0' );
		}

		if ( is_single() ) {
			wp_enqueue_script( 'cpr-article-js', get_template_directory_uri() . '/client/build/' . ai_get_versioned_asset( 'article.js' ), array( 'cpr-common-js' ), '1.0' );
			wp_enqueue_style( 'cpr-article-css', get_template_directory_uri() . '/client/build/' . ai_get_versioned_asset( 'article.css' ), array(), '1.0' );
		}
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_assets' );

/**
 * Enqueues scripts and styles for admin screens
 */
function enqueue_admin() {
	wp_enqueue_script( 'cpr-admin-js', get_template_directory_uri() . '/client/build/js/admin.bundle.js', array(), '1.0', true );
	wp_enqueue_style( 'cpr-admin-css', get_template_directory_uri() . '/client/build/css/admin.css', array(), '1.0' );
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_admin' );

/**
 * Removes scripts that could potentially cause style conflicts
 */
function dequeue_scripts() {
	wp_dequeue_style( 'jetpack-slideshow' );
	wp_dequeue_style( 'jetpack-carousel' );
}
add_action( 'wp_print_scripts', __NAMESPACE__ . '\dequeue_scripts' );
