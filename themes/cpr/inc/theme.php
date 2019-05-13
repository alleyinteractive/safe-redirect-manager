<?php
/**
 * Theme setup.
 *
 * @package CPR
 */

namespace CPR;

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as
 * indicating support post thumbnails.
 */
function theme_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 *
	 * load_theme_textdomain( 'cpr', THEME_PATH . '/languages' );
	 */

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Add styles to visual editor.
	add_editor_style( home_url( '/static/css/editor.css' ) );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// Set up theme's use of wp_nav_menu().
	register_nav_menus( array(
		'footer-1'           => __( 'Footer 1', 'cpr' ),
		'footer-2'           => __( 'Footer 2', 'cpr' ),
		'footer-3'           => __( 'Footer 3', 'cpr' ),
		'footer-4'           => __( 'Footer 4', 'cpr' ),
		'header'             => __( 'Homepage', 'cpr' ),
		'news'               => __( 'News', 'cpr' ),
		'classical'          => __( 'Classical', 'cpr' ),
		'indie'              => __( 'Indie', 'cpr' ),
		'primary-navigation' => __( 'Slideout Navigation', 'cpr' ),
	) );

	// Enable support for HTML5 components.
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core's custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 250,
		'width'       => 250,
		'flex-width'  => true,
		'flex-height' => true,
	) );

	// Disable comments by default.
	update_option( 'default_comment_status', false );
	update_option( 'default_ping_status', false );
	update_option( 'default_pingback_flag', false );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\theme_setup' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', __NAMESPACE__ . '\body_classes' );

/**
 * Return an array of content post types.
 *
 * @return array
 */
function get_content_post_types() {
	return [
		'post',
		'podcast-episode',
	];
}

/**
 * Get the site's accent color for a particular section.
 *
 * @param string $section Site section, default main.
 * @return string
 */
function get_site_color( string $section = 'main' ) {
	switch ( $section ) {
		case 'classical':
			return '#7F386C';

		case 'openair':
			return '#296795';

		case 'news':
		case 'main':
		default:
			return '#EF6530';
	}
}
