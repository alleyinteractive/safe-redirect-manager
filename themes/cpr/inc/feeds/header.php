<?php
/**
 * Feed Header.
 *
 * @package CPR
 */

$podcast_title       = ai_get_var( 'podcast_title' );
$podcast_url         = ai_get_var( 'podcast_url' );
$podcast_description = ai_get_var( 'podcast_description' );

?>

<title><?php echo esc_html( $podcast_title ); ?></title>
<link><?php echo esc_url( $podcast_url ); ?></link>
<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
<description><?php echo esc_html( $podcast_description ); ?></description>
<lastBuildDate><?php echo esc_html( mysql2date( 'D, d M Y H:i:s +0000', get_lastpostmodified( 'GMT' ), false ) ); ?></lastBuildDate>
<language><?php bloginfo_rss( 'language' ); ?></language>
<copyright><?php echo esc_html( $podcast_title ); ?></copyright>
