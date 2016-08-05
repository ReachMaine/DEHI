<?php
/*
Template name: Full Width boxed content
*/
/* mods 
*  5Aug16 zig - add  widget area for dehi-page-header.
*/
get_header(); ?>

<?php if ( is_active_sidebar( 'dehi-page-header') ) {
		
		dynamic_sidebar( 'dehi-page-header' );
		
}  ?>
<div class="dehi-content-container">
	<div id="content" role="main"  >

				<?php while ( have_posts() ) : the_post(); ?>

					<?php the_content(); ?>
				
				<?php endwhile; // end of the loop. ?>
				
	</div>
</div>
<?php get_footer(); ?>
