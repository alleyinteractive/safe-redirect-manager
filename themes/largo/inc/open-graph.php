<?php
/**
 * Adds appropriate open graph, twittercards, and google publisher tags
 * to the header based on the page type displayed
 *
 * @uses largo_twitter_url_to_username()
 * @since 0.3
 */
if ( ! function_exists( 'largo_opengraph' ) ) {
	function largo_opengraph() {

		global $post;

		// set a default thumbnail, if a post has a featured image use that instead
		if ( is_singular() && has_post_thumbnail( $post->ID ) ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$thumbnailURL = $image[0];
		} else if ( of_get_option( 'logo_thumbnail_sq' ) ) {
			$thumbnailURL = of_get_option( 'logo_thumbnail_sq' );
		} else {
			$thumbnailURL = false;
		}

		// start the output, some attributes will be the same for all page types ?>

		<meta name="twitter:card" content="summary">

		<?php
			if ( of_get_option( 'twitter_link' ) )
				echo '<meta name="twitter:site" content="@' . largo_twitter_url_to_username( of_get_option( 'twitter_link' ) ) . '">';
		?>

		<?php // output appropriate OG tags by page type
			if ( is_singular() ) {
				if ( have_posts() ) {
					the_post(); // we need to queue up the post to get the post specific info
					
					if ( get_the_author_meta( 'twitter' ) && !get_post_meta( $post->ID, 'largo_byline_text' ) )
						echo '<meta name="twitter:creator" content="@' . largo_twitter_url_to_username( get_the_author_meta( 'twitter' ) ) . '">';
					?>
					<meta property="og:title" content="<?php the_title(); ?>" />
					<meta property="og:type" content="article" />
					<meta property="og:url" content="<?php the_permalink(); ?>"/>
					<meta property="og:description" content="<?php echo strip_tags( esc_html( get_the_excerpt() ) ); ?>" />
					<meta name="description" content="<?php echo strip_tags( esc_html( get_the_excerpt() ) ); ?>" />
			<?php
				} // have_posts

				rewind_posts();

			} elseif ( is_front_page() ) { ?>

				<meta property="og:title" content="<?php bloginfo( 'name' ); echo ' - '; bloginfo( 'description' ); ?>" />
				<meta property="og:type" content="website" />
				<meta property="og:url" content="<?php echo home_url(); ?>"/>
				<meta property="og:description" content="<?php bloginfo( 'description' ); ?>" />
				<meta name="description" content="<?php bloginfo( 'description' ); ?>" />
		<?php
			} else {
		?>
				<meta property="og:title" content="<?php wp_title(); ?>" />
				<meta property="og:type" content="article" />
				<meta property="og:url" content="<?php echo esc_url( largo_get_current_url() ); ?>"/>
			<?php
				//let's try to get a better description when available
				if ( is_category() && category_description() ) {
					$description = category_description();
				} elseif ( is_author() ) {
					if ( have_posts() ) {
						the_post(); // we need to queue up the post to get the post specific info
						if ( get_the_author_meta( 'description' ) )
							$description = get_the_author_meta( 'description' );
					}
					rewind_posts();
				} else {
					$description = get_bloginfo( 'description' );
				}
				if ( $description ) {
					echo '<meta property="og:description" content="' . strip_tags( esc_html( $description ) ) . '" />';
					echo '<meta name="description" content="' . strip_tags( esc_html( $description ) ) . '" />';
				}
			} // else

			// a few more attributes that are common to all page types
			echo '<meta property="og:site_name" content="'  . get_bloginfo() . '" />';

			// thumbnail url
			if ( $thumbnailURL )
				echo '<meta property="og:image" content="' . esc_url( $thumbnailURL ) . '" />';

	}
}
// don't add this if Yoast is active
if ( ! class_exists( 'WPSEO_OpenGraph' ) ) {
	add_action( 'wp_head', 'largo_opengraph' );
}

/**
 * Filter wp_title() to add our custom metadata
 *
 * @since 0.6
 * @link https://github.com/INN/largo/issues/1470
 * @param Array $parts An array of title parts.
 * @return Array.
 */
function largo_wp_title_parts_filter( $parts ) {
	global $page, $paged;

	// Add the blog description for the home/front page.
	if ( is_home() || is_front_page() ) {
		$site_description = get_bloginfo( 'description', 'display' );
		if ( ! empty ( $site_description ) ) {
			$parts[] = $site_description;
		}
	}

	// Add a page number if necessary:
	if ( isset( $paged ) || isset( $page ) ) {
		if ( $paged >= 2 || $page >= 2 ) {
			$parts[] = sprintf(
				// translators: %1$s is the page number.
				__( 'Page %1$s' , 'largo' ),
				max( $paged, $page )
			);
		}
	}

	$parts[] = get_bloginfo( 'name' ); // Add the blog name.

	foreach ( $parts as $i => $part ) {
		if ( empty( $part ) ) {
			unset( $parts[$i] );
		}
	}

	return $parts;
}
add_filter( 'wp_title_parts', 'largo_wp_title_parts_filter' );

/**
 * Return to using |
 *
 * @since Largo 0.6
 * @link https://developer.wordpress.org/reference/functions/wp_get_document_title/
 */
add_filter( 'document_title_separator', function( $sep ) {
	return '|';
});
