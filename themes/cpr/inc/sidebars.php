<?php
/**
 * This file holds configuration settings for widget areas.
 *
 * @package Cpr
 */

namespace Cpr;

/**
 * Register widget areas.
 */
function widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'cpr' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here.', 'cpr' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', __NAMESPACE__ . '\widgets_init' );
