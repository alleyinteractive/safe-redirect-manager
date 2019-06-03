<?php
/**
 * This file holds configuration settings for widget areas.
 *
 * @package CPR
 */

namespace CPR;

/**
 * Register sidebars for the site.
 */
function register_sidebars() {
	$before_title  = '';
	$after_title   = '';
	$before_widget = '';
	$after_widget  = '';

	register_sidebar(
		[
			'name'          => __( 'News Sidebar', 'cpr' ),
			'id'            => 'news-sidebar',
			'description'   => __( 'News sidebar.', 'cpr' ),
			'before_widget' => $before_widget,
			'after_widget'  => $after_widget,
			'before_title'  => $before_title,
			'after_title'   => $after_title,
		]
	);

	register_sidebar(
		[
			'name'          => __( 'Classical Sidebar', 'cpr' ),
			'id'            => 'classical-sidebar',
			'description'   => __( 'Classical sidebar.', 'cpr' ),
			'before_widget' => $before_widget,
			'after_widget'  => $after_widget,
			'before_title'  => $before_title,
			'after_title'   => $after_title,
		]
	);

	register_sidebar(
		[
			'name'          => __( 'Indie Sidebar', 'cpr' ),
			'id'            => 'indie-sidebar',
			'description'   => __( 'Indie sidebar.', 'cpr' ),
			'before_widget' => $before_widget,
			'after_widget'  => $after_widget,
			'before_title'  => $before_title,
			'after_title'   => $after_title,
		]
	);
}
add_action( 'widgets_init', __NAMESPACE__ . '\register_sidebars' );

/**
 * Unregister Tribe Events Widget.
 */
function unregister_tribe_widget() {
	unregister_widget( 'Tribe__Events__List_Widget' );
}
add_action( 'widgets_init', __NAMESPACE__ . '\unregister_tribe_widget', 90 );

/**
 * Unregister Widgets.
 */
function unregister_widgets() {
	// WP Widgets.
	unregister_widget( 'WP_Widget_Archives' );
	unregister_widget( 'WP_Widget_Calendar' );
	unregister_widget( 'WP_Widget_Categories' );
	unregister_widget( 'WP_Widget_Meta' );
	unregister_widget( 'WP_Widget_Pages' );
	unregister_widget( 'WP_Widget_Recent_Comments' );
	unregister_widget( 'WP_Widget_Recent_Posts' );
	unregister_widget( 'WP_Widget_RSS' );
	unregister_widget( 'WP_Widget_Search' );
	unregister_widget( 'WP_Widget_Tag_Cloud' );
	unregister_widget( 'WP_Widget_Custom_HTML' );
	unregister_widget( 'WP_Nav_Menu_Widget' );
	unregister_widget( 'WP_Widget_Media_Video' );

	// Jetpack widgets.
	unregister_widget( 'WPCOM_Widget_Facebook_LikeBox' );
	unregister_widget( 'Jetpack_Gravatar_Profile_Widget' );
	unregister_widget( 'Jetpack_Image_Widget' );
	unregister_widget( 'Jetpack_Widget_Social_Icons' );
	unregister_widget( 'Jetpack_RSS_Links_Widget' );
	unregister_widget( 'Jetpack_Readmill_Widget' );
	unregister_widget( 'Jetpack_Widget_Twitter' );
	unregister_widget( 'Jetpack_Upcoming_Events_Widget' );
	unregister_widget( 'Jetpack_Top_Posts_Widget' );
	unregister_widget( 'Jetpack_Flickr_Widget' );
	unregister_widget( 'Jetpack_Twitter_Timeline_Widget' );
	unregister_widget( 'wpcom_social_media_icons_widget' );
	unregister_widget( 'Milestone_Widget' );
	unregister_widget( 'Jetpack_My_Community_Widget' );
	unregister_widget( 'Jetpack_Internet_Defense_League_Widget' );
	unregister_widget( 'WPCOM_Widget_GooglePlus_Badge' );
	unregister_widget( 'Jetpack_EU_Cookie_Law_Widget' );
	unregister_widget( 'WPCOM_Widget_Goodreads' );
	unregister_widget( 'Jetpack_Display_Posts_Widget' );
	unregister_widget( 'Jetpack_Google_Translate_Widget' );
	unregister_widget( 'Jetpack_Contact_Info_Widget' );
	unregister_widget( 'Jetpack_Blog_Stats_Widget' );
	unregister_widget( 'Jetpack_Gallery_Widget' );
	unregister_widget( 'Jetpack_Subscriptions_Widget' );
}
add_action( 'widgets_init', __NAMESPACE__ . '\unregister_widgets' );

/**
 * Register widgets.
 */
function register_widgets() {
	if ( ! class_exists( 'FM_Widget' ) ) {
		return;
	}

	register_widget( __NAMESPACE__ . '\Events_Widget' );
	register_widget( __NAMESPACE__ . '\External_Link_Widget' );
}
add_action( 'widgets_init', __NAMESPACE__ . '\register_widgets' );
