<?php
/**
 * XML RSS feed for the CPR app.
 *
 * @package CPR
 */

namespace CPR;

$args = [];

switch ( get_query_var( 'custom-feed-slug' ) ) {
	/**
	 * 15 most recent classical posts.
	 */
	case 'all_classical':
		$args['posts_per_page'] = 15;
		$args['tax_query']      = [
			[
				'taxonomy' => 'section',
				'field'    => 'slug',
				'terms'    => 'classical',
			],
		];
		break;

	/**
	 * 15 most recent news posts.
	 */
	case 'all_news':
		$args['posts_per_page'] = 15;
		$args['tax_query']      = [
			[
				'taxonomy' => 'section',
				'field'    => 'slug',
				'terms'    => 'news',
			],
		];
		break;

	/**
	 * 15 most recent news posts.
	 */
	case 'all_openair':
		$args['posts_per_page'] = 15;
		$args['tax_query']      = [
			[
				'taxonomy' => 'section',
				'field'    => 'slug',
				'terms'    => 'indie',
			],
		];

		break;

	/**
	 * The 3 posts at the top of the classical homepage.
	 */
	case 'featured_content_classical':
		break;

	/**
	 * The 5 posts at the top of the homepage.
	 */
	case 'featured_content_home':
		break;

	/**
	 * The 1st post at the top of the homepage.
	 */
	case 'featured_content_home_main':
		break;

	/**
	 * The 5 posts at the top of the news homepage.
	 */
	case 'featured_content_news':
		break;

	/**
	 * The 3 posts at the top of the indie homepage.
	 */
	case 'featured_content_openair':
		break;

	default:
		wp_safe_redirect( home_url(), 301 );
		exit();
}

// Get posts to populate this feed.
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$feed_items = new \WP_Query( $args );

// Set the proper header.
header( 'Content-Type: application/xml; charset=utf-8' );
echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<rss version="2.0"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:media="https://search.yahoo.com/mrss/"
	xmlns:rss="http://purl.org/rss/1.0/"
	<?php
	/**
	 * Fires at the end of the RSS root to add namespaces.
	 *
	 * @since 2.0.0
	 */
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	do_action( 'rss2_ns' );
	?>
>
	<channel>
		<?php
		get_template_part( 'inc/feeds/header' );

		if ( $feed_items->have_posts() ) :
			while ( $feed_items->have_posts() ) :
				$feed_items->the_post();
				?>
				<item>
					<guid isPermaLink="false"><?php the_guid(); ?></guid>
					<title><?php the_title_rss(); ?></title>
					<image>
						<url><?php echo esc_html( the_post_thumbnail_url() ); ?></url>
						<caption><?php echo esc_html( the_post_thumbnail_caption() ); ?></caption>
					</image>
					<link><?php echo esc_url( the_permalink_rss() ); ?></link>
					<pubDate><?php echo esc_html( mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ) ); ?></pubDate>
					<updatedDate><?php echo esc_html( mysql2date( 'D, d M Y H:i:s +0000', get_the_modified_time( 'Y-m-d H:i:s' ), false ) ); ?></updatedDate>
					<author><?php echo esc_html( get_the_author() ); ?></author>
					<dc:creator><?php echo esc_html( get_the_author() ); ?></dc:creator>
					<?php the_category_rss( 'rss2' ); ?>
					<description>
						<![CDATA[ <?php echo esc_html( the_excerpt_rss() ); ?> ]]>
					</description>
				</item>
				<?php
			endwhile;
		endif;
		?>
	</channel>
</rss>
