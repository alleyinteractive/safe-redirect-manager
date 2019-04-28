<?php
/**
 * Plugin Name: Denverite MU Loader
 * Description: Wrapper plugin to manually require non-mu compatible plugins
 * Author: Alley Interactive
 * Version: 1.0
 */

// Denverite is blog #2.
if ( 2 !== get_current_blog_id() ) {
	return;
}

/**
 * Composer autoload
 *
 * Loads Composer dependencies as well as Pedestal classes
 */
require_once ABSPATH . '/wp-content/vendor/autoload.php';

// Denverite required plugins.
$plugins = [
	'/co-authors-plus/co-authors-plus.php',
	'/posts-to-posts/posts-to-posts.php',
	'/shortcake-bakery/shortcake-bakery.php',
	'/shortcode-ui/shortcode-ui.php',
	'/wordpress-fieldmanager/fieldmanager.php',
	'/wp-redis/wp-redis.php',
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

/*
 * The BWP Google Sitemaps Plugin has various options we want to
 * control once in our codebase instead of on each individual site.
 *
 * Use http://www.unserialize.com/ to unserialize value from database
 */

add_filter( 'pre_option_bwp_gxs_generator', function( $value ) {
	$bwp_option = [
		'input_cache_dir'               => '',
		'input_item_limit'              => 5005,
		'input_split_limit_post'        => 0,
		'input_custom_xslt'             => '',
		'input_ping_limit'              => 100,
		'enable_sitemap_date'           => '',
		'enable_sitemap_taxonomy'       => 'yes',
		'enable_sitemap_external'       => '',
		'enable_sitemap_author'         => 'yes',
		'enable_sitemap_site'           => 'yes',
		'enable_exclude_posts_by_terms' => '',
		'enable_sitemap_split_post'     => 'yes',
		'enable_ping'                   => 'yes',
		'enable_ping_google'            => 'yes',
		'enable_ping_bing'              => 'yes',
		'enable_xslt'                   => '',
		'enable_credit'                 => '',
		'select_default_freq'           => 'daily',
		'select_default_pri'            => '1',
		'select_min_pri'                => '0.1',
		'input_exclude_post_type'       => 'pedestal_link',
		'input_exclude_post_type_ping'  => '',
		'input_exclude_taxonomy'        => 'category,post_tag,pedestal_story_type,pedestal_slot_item_type',
	];
	return $bwp_option;
});

add_filter( 'pre_option_bwp_gxs_extensions', function( $value ) {
	$bwp_option = [
		'enable_image_sitemap'       => 'yes',
		'enable_news_sitemap'        => 'yes',
		'enable_news_ping'           => 'yes',
		'enable_news_keywords'       => '',
		'enable_news_multicat'       => '',
		'select_news_post_type'      => 'pedestal_article',
		'select_news_taxonomy'       => '',
		'select_news_lang'           => 'en',
		'select_news_keyword_source' => '',
		'select_news_cat_action'     => 'inc',
		'select_news_cats'           => '',
		'input_news_name'            => '',
		'input_news_age'             => 3,
		'input_news_genres'          => [],
		'input_image_post_types'     => 'page,pedestal_article,pedestal_event,pedestal_link,pedestal_factcheck,pedestal_whosnext,pedestal_story,pedestal_topic,pedestal_person,pedestal_org,pedestal_place,pedestal_locality',
	];
	return $bwp_option;
});

/**
 * If S3 Uploads plugin is in use then fix the path of uploaded items to S3 so
 * it mirrors WordPress' file structure
 * (aka <BUCKET>/uploads/ --> <BUCKET>/wp-content/uploads/)
 */
if ( defined( 'S3_UPLOADS_BUCKET' ) ) {
	add_filter( 'upload_dir', function( $dirs = [] ) {
		$old_str = S3_UPLOADS_BUCKET . '/uploads/';
		$new_str = S3_UPLOADS_BUCKET . '/wp-content/uploads/';
		$dirs['path']    = str_replace( $old_str, $new_str, $dirs['path'] );
		$dirs['basedir'] = str_replace( $old_str, $new_str, $dirs['basedir'] );

		if ( defined( 'S3_UPLOADS_BUCKET_URL' ) ) {
			$old_str = S3_UPLOADS_BUCKET_URL . '/uploads/';
			$new_str = S3_UPLOADS_BUCKET_URL . '/wp-content/uploads/';
		} else {
			$old_str = 's3.amazonaws.com/uploads';
			$new_str = 's3.amazonaws.com/wp-content/uploads';
		}

		$keys_to_replace = [
			'url',
			'baseurl',
			'relative',
		];
		foreach ( $keys_to_replace as $key ) {
			if ( ! empty( $dirs[ $key ] ) ) {
				$dirs[ $key ] = str_replace( $old_str, $new_str, $dirs[ $key ] );
			}
		}

		return $dirs;
	}, 11 );
}
