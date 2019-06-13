<?php
/**
 * Template for category archive pages
 *
 * @package Largo
 * @since 0.4
 * @filter largo_partial_by_post_type
 */
get_header();

global $tags, $paged, $post, $shown_ids;

$title = single_cat_title( '', false );
$description = category_description();
$rss_link = get_category_feed_link( get_queried_object_id() );
$posts_term = of_get_option( 'posts_term_plural', 'Stories' );
$queried_object = get_queried_object();
?>

<div class="clearfix">
	<header class="archive-background clearfix">
		<a class="rss-link rss-subscribe-link" href="<?php echo $rss_link; ?>"><?php echo __( 'Subscribe', 'largo' ); ?> <i class="icon-rss"></i></a>
		<?php
			$post_id = largo_get_term_meta_post( $queried_object->taxonomy, $queried_object->term_id );
			largo_hero( $post_id );
		?>
		<h1 class="page-title"><?php echo $title; ?></h1>
		<div class="archive-description"><?php echo $description; ?></div>
		<?php do_action( 'largo_category_after_description_in_header' ); ?>
		<?php get_template_part( 'partials/archive', 'category-related' ); ?>
	</header>

	<?php if ( $paged < 2 && of_get_option( 'hide_category_featured' ) == '0' ) {
		$featured_posts = largo_get_featured_posts_in_category( $wp_query->query_vars['category_name'] );
		if ( count( $featured_posts ) > 0 ) {
			$top_featured = $featured_posts[0];
			$shown_ids[] = $top_featured->ID; ?>

			<div class="primary-featured-post">
				<?php largo_render_template(
					'partials/archive',
					'category-primary-feature',
					array( 'featured_post' => $top_featured )
				); ?>
			</div>

			<?php
			
			/*
			 * NOTE: If you are trying to accomplish something other than displaying no featured posts 
			 * or displaying 5 featured posts, you will need to modify the `largo_category_archive_posts` function
			 * located at https://github.com/INN/largo/blob/master/inc/featured-content.php#L167, since it modifies the 
			 * category posts query in order to obtain the category featured posts. If you modify this section, but not 
			 * `largo_category_archive_posts`, some of your posts will go missing on the category page.
			 *
			 * There are two primary ways to replace the `largo_category_archive_posts` function:
			 *
			 * 1. Modify the `largo_category_archive_posts` function to not perform the featured post removal from certain queries
			 *      - This will require using `remove_action( 'pre_get_posts', 'largo_category_archive_posts', 15 )` to remove
			 *        the current function and using `add_action` to add in your replacement function.
			 * 
			 * 2. Modify the `largo_category_archive_posts` function to use a modified `largo_get_featured_posts_in_category`
			 *    function that will return a different selection of posts for the specified category.
			 *      - This will also require using `remove_action( 'pre_get_posts', 'largo_category_archive_posts', 15 )` to remove
			 *        the current function and using `add_action` to add in your replacement function.
			 *      - This also requires creating a modified version of the `largo_get_featured_posts_in_category` function
			 *        and replacing it in templates where it is used.
			 *      - In order to change the number of posts returned by your modified `largo_category_archive_posts` function,
			 *        ->get( 'category_name' ) );`
			 *        to reflect your preferred number of featured posts.
			 */

			$secondary_featured = array_slice( $featured_posts, 1 );
			if ( count( $secondary_featured ) > 0 ) { ?>
				<div class="secondary-featured-post">
					<div class="row-fluid clearfix"><?php
						foreach ( $secondary_featured as $idx => $featured_post ) {
								$shown_ids[] = $featured_post->ID;
								largo_render_template(
									'partials/archive',
									'category-secondary-feature',
									array( 'featured_post' => $featured_post )
								);
						} ?>
					</div>
				</div>
		<?php }
	}
} ?>
</div>

<div class="row-fluid clearfix">
	<div class="stories span8" role="main" id="content">
		
	<?php 
		do_action( 'largo_before_category_river' );
		if ( have_posts() ) {
			$counter = 1;
			while ( have_posts() ) {
				the_post();
				$post_type = get_post_type();
				$partial = largo_get_partial_by_post_type( 'archive', $post_type, 'archive' );
				get_template_part( 'partials/content', $partial );
				do_action( 'largo_loop_after_post_x', $counter, $context = 'archive' );
				$counter++;
			}
			largo_content_nav( 'nav-below' );
		} elseif ( count($featured_posts) > 0 ) {
			// do nothing
			// We have n > 1 posts in the featured header
			// It's not appropriate to display partials/content-not-found here.
		} else {
			get_template_part( 'partials/content', 'not-found' );
		}
		do_action( 'largo_after_category_river' );
	?>
	</div>
	<?php get_sidebar(); ?>
</div>

<?php get_footer();
