<?php
/**
 * Custom functions go here
 */

// Grab any custom requirements
require_once 'inc/reqs.php';

/**
 * override original byline function in largo/inc/post-tags.php
 * @param bool $echo
 * @param bool $exclude_date
 * @param null $post
 * @param bool $exclude_author whether or not to include name in byline
 * @return Largo_Byline|Largo_CoAuthors_Byline|Largo_Custom_Byline|mixed|void
 */
function onsomething_byline( $echo = true, $exclude_date = false, $post = null,  $exclude_author = false) {

    // Get the post ID
    if (!empty($post)) {
        if (is_object($post))
            $post_id = $post->ID;
        else if (is_numeric($post))
            $post_id = $post;
    } else {
        $post_id = get_the_ID();

        if ( WP_DEBUG || LARGO_DEBUG ) {
            _doing_it_wrong( 'onsomething_byline', 'onsomething_byline must be called with a post or post ID specified as the third argument. For more information, see https://github.com/INN/largo/issues/1517 .', '0.6' );
        }
    }

    // Set us up the options
    // This is an array of things to allow us to easily add options in the future
    $options = array(
        'post_id' => $post_id,
        'values' => get_post_custom( $post_id ),
        'exclude_date' => $exclude_date,
        'exclude_author' => $exclude_author,
    );

    $byline = new Onsomething_Custom_Byline($options);

    /**
     * Filter the largo_byline output text to allow adding items at the beginning or the end of the text.
     *
     * @since 0.5.4
     * @param string $partial The HTML of the output of largo_byline(), before the edit link is added.
     * @param array $array Associative array of argument name => argument value, with the arguments passed to largo_byline(). Since https://github.com/INN/largo/issues/1656
     * @link https://github.com/INN/Largo/issues/1070
     */
    $byline = apply_filters(
        'onsomething_byline',
        $byline,
        array(
            'echo' => $echo,
            'exclude_date' => $exclude_date,
            'post' => $post
        )
    );

    if ( $echo ) {
        echo $byline;
    }
    return $byline;
}
/**
 * Register a custom homepage layout
 *
 * @see "homepages/layouts/onsomething_homepage_layout.php"
 */
function register_custom_homepage_layout() {
    include_once __DIR__ . '/homepages/layouts/onsomething_homepage_layout.php';
    register_homepage_layout('OnsomethingHomepageLayout');
}
add_action('init', 'register_custom_homepage_layout', 0);


/**
 * Include compiled style.css
 */
//function child_stylesheet() {
//  wp_dequeue_style( 'largo-child-styles' );
//
//  $suffix = (LARGO_DEBUG)? '' : '.min';
//  wp_enqueue_style( 'onsomething', get_stylesheet_directory_uri().'/css/child' . $suffix . '.css' );
//
//}
//add_action( 'wp_enqueue_scripts', 'child_stylesheet', 20 );

/**
 * Register a custom widget
 *
 * @see "inc/widgets/your_simple_widget.php"
 */
//function register_custom_widget() {
//  include_once __DIR__ . '/inc/widgets/your_simple_widget.php';
//  register_widget('your_simple_widget');
//}
//add_action('widgets_init', 'register_custom_widget', 1);
