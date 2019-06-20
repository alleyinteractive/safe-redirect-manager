<?php
/**
 * Manage static assets.
 *
 * @package CPR
 */

namespace CPR;

/**
 * Check if we're in FE development mode
 *
 * @return bool whether or not we're in development mode
 */
function is_dev() {
	return (
		( ! empty( $_GET['fe-dev'] ) && 'on' === $_GET['fe-dev'] ) || // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification, WordPress.VIP.SuperGlobalInputUsage.AccessDetected
		! empty( $_COOKIE['fe-dev'] ) // phpcs:ignore WordPress.VIP.RestrictedVariables.cache_constraints___COOKIE, WordPress.VIP.SuperGlobalInputUsage.AccessDetected
	);
}

/**
 * Set cookie to truthy value if fe-dev param is set to 'on', otherwise set cookie to falsy value
 */
function set_dev_cookie() {
	if ( ! empty( $_GET['fe-dev'] ) && 'off' === $_GET['fe-dev'] ) { // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification, WordPress.VIP.SuperGlobalInputUsage.AccessDetected
		setcookie( 'fe-dev', '0', 0, COOKIEPATH, COOKIE_DOMAIN, is_ssl() ); // phpcs:ignore WordPressVIPMinimum.VIP.RestrictedFunctions.cookies_setcookie
		$_COOKIE['fe-dev'] = null; // phpcs:ignore WordPress.VIP.RestrictedVariables.cache_constraints___COOKIE
	} elseif ( is_dev() ) {
		setcookie( 'fe-dev', '1', 0, COOKIEPATH, COOKIE_DOMAIN, is_ssl() ); // phpcs:ignore WordPressVIPMinimum.VIP.RestrictedFunctions.cookies_setcookie
	}
}
add_action( 'init', __NAMESPACE__ . '\set_dev_cookie' );

/**
 * Get the version for a given asset.
 *
 * @param string $asset_path Entry point and asset type separated by a '.'.
 * @return string The asset version.
 */
function ai_get_versioned_asset_path( $asset_path ) {
	static $asset_map;

	// Create public path.
	$base_path = is_dev() ?
		'//localhost:8080/client/build/' :
		CPR_URL . '/client/build/';

	if ( ! isset( $asset_map ) ) {
		$asset_map_file = CPR_PATH . '/client/build/assetMap.json';

		if ( file_exists( $asset_map_file ) && 0 === validate_file( $asset_map_file ) ) {
			ob_start();
			include $asset_map_file; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.IncludingFile
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
	$versioned_path            = isset( $asset_map[ $entrypoint ][ $type ] ) ? $asset_map[ $entrypoint ][ $type ] : false;

	if ( $versioned_path ) {
		return $base_path . $versioned_path;
	}

	return '';
}

/**
 * Enqueues scripts and styles for admin screens
 */
function enqueue_admin() {

	// Front-end.
	wp_enqueue_script(
		'cpr-common-js',
		ai_get_versioned_asset_path( 'common.js' ),
		[ 'jquery' ],
		'1.0',
		true
	);

	wp_enqueue_style(
		'cpr-common-css',
		ai_get_versioned_asset_path( 'common.css' ),
		[],
		'1.0'
	);

	// Admins.
	wp_enqueue_script(
		'cpr-admin-js',
		ai_get_versioned_asset_path( 'admin.js' ),
		[
			'jquery',
			'wp-api-fetch',
			'wp-blocks',
			'wp-components',
			'wp-data',
			'wp-editor',
			'wp-element',
			'wp-i18n',
			'wp-notices',
			'wp-edit-post',
		],
		'1.0',
		true
	);

	wp_enqueue_style( 'cpr-admin-css', ai_get_versioned_asset_path( 'admin.css' ), [], '1.0' );
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_admin' );
