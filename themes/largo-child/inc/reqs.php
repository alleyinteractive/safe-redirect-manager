<?php

/**
 * Just a place to consolidate any custom requires/imports for our OnSomething child theme
 */

$reqs = [
    '/inc/Onsomething_Custom_Byline.php',
    '/inc/post-social.php',
];

// Itereate over our array and require each file
foreach ($reqs as $include ) {
    if (0 === validate_file(get_stylesheet_directory() . $include)) {
        require_once(get_stylesheet_directory() . $include);
    }
}
