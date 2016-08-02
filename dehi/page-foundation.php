<?php
/*
Template name: Foundation Page Left sidebar
* 
* 28July16 zig - add widget area to top of left sidebar page template for the foundation pages.
*/
get_header(); ?>
<?php if ( is_active_sidebar( 'dehi-foundation-header') ) {
		//echo '<div class="dehi-foundation-header">';
		dynamic_sidebar( 'dehi-foundation-header' );
		//echo "</div>";
}  ?>

<?php if( has_excerpt() ) { ?>
<div class="page-header">
	<?php the_excerpt(); ?>
</div>
<?php } ?>

<div  class="page-wrapper page-left-sidebar">
<div class="row">

<div id="content" class="large-9 right columns dehi-foundation" role="main">
	<div class="page-inner">
			<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); ?>

			<?php endwhile; // end of the loop. ?>
	</div><!-- .page-inner -->
</div><!-- end #content large-9 left -->

<div id="dehi-foundation-side" class="large-3 columns left">
<?php get_sidebar(); ?>
</div><!-- end sidebar -->

</div><!-- end row -->
</div><!-- end page-right-sidebar container -->


<?php get_footer(); ?>
