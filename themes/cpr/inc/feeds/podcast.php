<?php
/**
 * XML RSS feed for the CPR podcasts.
 *
 * @package CPR
 */

namespace CPR;

// We need to get _all_ the episodes for itunes and other feed use. Disregard
// that we're not supposed to do this.
$cpr_args = [
	'posts_per_page' => -1,
];

$term_slug     = get_query_var( 'custom-feed-slug' );
$term_taxonomy = 'podcast';

switch ( $term_slug ) {

	// Map Beethoven 9 podcast to just `beethoven`.
	case 'beethoven':
		$term_slug = 'the-beethoven-9';
		break;

	case 'colorado_art_report':
		$term_slug     = '';
		$term_taxonomy = '';
		break;

	// Map Colorado Matters to the show.
	case 'colorado_matters':
		$term_slug     = 'colorado-matters';
		$term_taxonomy = 'show';
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
		$possible_podcast = get_term_by( 'slug', $term_slug, 'podcast' );
		if ( $possible_podcast instanceof \WP_Term ) {
			$term_taxonomy = 'podcast';
			$term_slug     = $possible_podcast->slug;
		} else {
			// Check if there's a show with this slug.
			$possible_show = get_term_by( 'slug', $term_slug, 'show' );
			if ( $possible_show instanceof \WP_Term ) {
				$term_taxonomy = 'show';
				$term_slug     = $possible_show->slug;
			} else {
				wp_safe_redirect( home_url(), 301 );
				exit();
			}
		}
		break;
}

$cpr_args['tax_query'] = [ // phpcs:ignore
	[
		'taxonomy' => $term_taxonomy,
		'field'    => 'slug',
		'terms'    => $term_slug,
	],
];

// Get posts to populate this feed.
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$feed_items = new \WP_Query( $cpr_args );

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
	xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"

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

				$post_id = get_the_ID();
				$meta_id = get_post_meta( $post_id, 'audio_id', true );
				if ( ! empty( $meta_id ) ) {
					$meta_id = get_post_meta( $post_id, 'mp3_id', true );
				}

				$audio      = wp_get_attachment_url( $meta_id );
				$audio_meta = get_post_meta( $meta_id, '_wp_attachment_metadata', true );
				$podcast    = get_term_by( 'slug', $term_slug, 'podcast' );
				?>
				<item>
					<guid isPermaLink="false"><?php the_guid(); ?></guid>
					<title><?php the_title_rss(); ?></title>
					<source url="<?php self_link(); ?>"><?php echo esc_html( $podcast->name ); ?></source>
					<link><?php echo esc_url( the_permalink_rss() ); ?></link>
					<pubDate><?php echo esc_html( mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ) ); ?></pubDate>
					<description>
						<![CDATA[ <?php echo esc_html( the_excerpt_rss() ); ?> ]]>
					</description>
					<itunes:subtitle><?php the_title_rss(); ?></itunes:subtitle>
					<itunes:summary><![CDATA[ <?php echo esc_html( the_excerpt_rss() ); ?> ]]></itunes:summary>

					<?php if ( ! empty( $audio ) ) : ?>
						<enclosure
							url="<?php echo esc_url( $audio ); ?>"
							length="<?php echo esc_attr( $audio_meta['length'] ?? 0 ); ?>"
							type="<?php echo esc_attr( $audio_meta['mime_type'] ?? '' ); ?>"
						/>

						<itunes:duration><?php echo esc_html( $audio_meta['length_formatted'] ?? '00:00' ); ?></itunes:duration>
					<?php endif; ?>
				</item>
				<?php
			endwhile;
		endif;
		?>
	</channel>
</rss>
