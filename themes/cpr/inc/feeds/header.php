<?php
/**
 * Feed Header.
 *
 * @package CPR
 */
?>

<title><?php echo esc_html( bloginfo_rss( 'name' ) ); ?></title>
<link><?php echo esc_url( bloginfo_rss( 'url' ) ); ?></link>
<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
<description><?php bloginfo_rss( 'description' ); ?></description>
<lastBuildDate><?php echo esc_html( mysql2date( 'D, d M Y H:i:s +0000', get_lastpostmodified( 'GMT' ), false ) ); ?></lastBuildDate>
<language><?php bloginfo_rss( 'language' ); ?></language>
