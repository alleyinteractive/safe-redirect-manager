<?php
/**
 * The main template file.
 *
 * @package CPR
 */
?>

<?php
if ( is_home() && ! is_front_page() ) :
	?>
	<header>
		<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
	</header>
	<?php
endif;

while ( have_posts() ) :
	the_post();

	get_template_part( 'template-parts/content', get_post_type() );

endwhile;

the_posts_navigation();
