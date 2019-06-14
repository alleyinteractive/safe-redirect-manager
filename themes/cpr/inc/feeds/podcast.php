<?php
/**
 * XML RSS feed for the CPR app.
 *
 * @package CPR
 */

namespace CPR;

// We need to get _all_ the episodes for itunes and other feed use. Disregard
// that we're not supposed to do this.
$args = [
	'posts_per_page' => -1,
];

$term_slug = get_query_var( 'custom-feed-slug' );
$taxonomy  = 'podcast';

switch ( get_query_var( 'custom-feed-slug' ) ) {

	// Map Beethoven 9 podcast to just `beethoven`
	case 'beethoven':
		$term_slug = 'the-beethoven-9';
		break;

	case 'colorado_art_report':
		$term_slug = '';
		$taxonomy  = '';
		break;

	// Map Colorado Matters to the show.
	case 'colorado_matters':
		$term_slug = 'colorado-matters';
		$taxonomy  = 'show';
		break;

	case 'centennial-sounds':
	case 'inside-track':
	case 'openair-sessions':
	case 'purplish':
	case 'the-great-composers':
	case 'the-playlist-league':
	case 'the-taxman':
	case 'whos-gonna-govern':
		// These podcasts have the same slug as url, so just keep moving.
		break;


	default:
		// Check if there's a podcast with this slug.
		$possible_podcast = get_term_by( 'slug', get_query_var( 'custom-feed-slug' ), 'podcast' );
		if ( $possible_podcast instanceof \WP_Term ) {
			$taxonomy  = 'podcast';
			$term_slug = $possible_podcast->slug;
		} else {
			// Check if there's a show with this slug.
			$possible_show    = get_term_by( 'slug', get_query_var( 'custom-feed-slug' ), 'show' );
			if ( $possible_show instanceof \WP_Term ) {
				$taxonomy  = 'show';
				$term_slug = $possible_show->slug;
			} else {
				wp_safe_redirect( home_url(), 301 );
				exit();
			}
		}
		break;
}

$args['tax_query'] = [
	[
		'taxonomy' => $taxonomy,
		'field'    => 'slug',
		'terms'    => $term_slug,
	],
];

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
