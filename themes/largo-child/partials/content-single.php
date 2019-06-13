<?php
/**
 * The template for displaying content in the single.php template
 **/

// Let's put our floating social icons in place
function enqueue_custom_script() {
    $version = '0.1.0';
    wp_enqueue_script(
        'largo-child',
        get_theme_file_uri ('/js/floating-social-buttons.js'),
        array('jquery'),
        $version,
        true
    );
}
add_action('wp_enqueue_scripts', 'enqueue_custom_script');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'hnews item' ); ?> itemscope itemtype="https://schema.org/Article">
	<?php do_action('largo_before_post_header'); ?>
	<header>
		<h1 class="entry-title" itemprop="headline"><?php the_title(); ?></h1>
		<?php if ( $subtitle = get_post_meta( $post->ID, 'subtitle', true ) ) : ?>
			<h2 class="subtitle"><?php echo $subtitle ?></h2>
		<?php endif; ?>
		<h5 class="byline"><?php onsomething_byline( true, false, get_the_ID(), true ); ?></h5>
        <?php largo_post_metadata( $post->ID ); ?>
	</header><!-- / entry header -->
	<?php
		do_action('largo_after_post_header');
		largo_hero(null,'span12');
		do_action('largo_after_hero');
	?>
	<?php get_sidebar(); ?>
	<section class="entry-content clearfix" itemprop="articleBody">
		<?php largo_entry_content( $post ); ?>
        <?php if ( ! of_get_option( 'single_social_icons' ) == false ) {
            largo_post_social_links();
        } ?>
	</section>
	<?php do_action('largo_after_post_content'); ?>

</article>
