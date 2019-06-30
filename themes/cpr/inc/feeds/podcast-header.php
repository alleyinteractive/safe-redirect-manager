<?php
/**
 * Podcast Feed Header.
 *
 * @package CPR
 */

$podcast_id    = ai_get_var( 'podcast_id' );
$podcast_title = ai_get_var( 'podcast_title' );
$image_url     = wp_get_attachment_url( absint( get_post_meta( $podcast_id, '_thumbnail_id', true ) ) );

if ( ! empty( $image_url ) ) : ?>
	<image>
		<url><?php echo esc_url( $image_url ); ?></url>
		<title><?php echo esc_html( $podcast_title ); ?></title>
		<link><?php echo esc_url( bloginfo_rss( 'url' ) ); ?></link>
	</image>
<?php endif; ?>

<itunes:subtitle>From <?php echo esc_html( bloginfo_rss( 'name' ) ); ?></itunes:subtitle>
<itunes:summary><?php bloginfo_rss( 'description' ); ?></itunes:summary>
<itunes:category text="Music" />
<itunes:keywords>Colorado Public Radio, CPR Classical, Colorado, Classical Music, Great Composers, Mozart, Amadeus Mozart, Rachmaninoff</itunes:keywords>

<?php if ( ! empty( $image_url ) ) : ?>
	<itunes:image href="<?php echo esc_url( $image_url ); ?>" />
<?php endif; ?>
<itunes:author><?php echo esc_html( bloginfo_rss( 'name' ) ); ?></itunes:author>
<itunes:explicit>no</itunes:explicit>
<itunes:owner>
	<itunes:email>knguyen@cpr.org</itunes:email>
	<itunes:name>Kim Nguyen</itunes:name>
</itunes:owner>
