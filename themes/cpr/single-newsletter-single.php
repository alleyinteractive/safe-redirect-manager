<?php
/**
 * The main newsletter template file.
 *
 * @package CPR
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<body>
<?php echo get_post_meta( get_the_ID(), 'newsletter_html', true ); ?>
</body>
</html>
