<?php

include_once get_template_directory() . '/homepages/homepage-class.php';

class OnsomethingHomepageLayout extends Homepage
{
    function __construct($options = array())
    {
        $defaults = array(
            'name' => __('On Something Homepage Layout', 'largo'),
            'description' => __('On Something Podcast Theme Layout', 'https://onsomething.org'),
            'template' => get_stylesheet_directory() . '/homepages/templates/onsomething_homepage_template.php',
            'assets' => array(
                // Currently all customn styles in onsomething.less - leaving this here as example
                // for homepage CSS enqueuing :: 20190611 JF
//                array(
//                    'onsomething_homepage_css',
//                    get_stylesheet_directory_uri() . '/homepages/assets/css/onsomething_homepage.min.css',
//                    array()
//                ),
                array(
                    'onsomething_homepage_js',
                    get_stylesheet_directory_uri() . '/homepages/assets/js/onsomething_homepage.js',
                    array('jquery')
                )
            ),
        );
        $options = array_merge($defaults, $options);
        parent::__construct($options);
    }

    function content()
    {
        // content to return to the browser for rendering
    }
}
