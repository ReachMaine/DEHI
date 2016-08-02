<?php 
// override of plugable flatsome functions 


if ( ! function_exists( 'flatsome_posted_on' ) ) {

    /**
     * Prints HTML with meta information for the current post-date/time and author.
     * mods:
     *     12Nov15 zig - dont show author in meta 
     */
    function flatsome_posted_on() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf( $time_string,
            esc_attr( get_the_date( 'c' ) ),
            esc_html( get_the_date() ),
            esc_attr( get_the_modified_date( 'c' ) ),
            esc_html( get_the_modified_date() )
        );

        $posted_on = sprintf(
            esc_html_x( 'Posted on %s', 'post date', 'flatsome' ),
            '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
        );
        /* $byline = sprintf(
            esc_html_x( 'by %s', 'post author', 'flatsome' ),
            '<span class="meta-author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
        );

        echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';
        */
        echo '<span class="posted-on">' . $posted_on . '</span>';
    }
} // end if defined flatsome_posted_on 

/**** add meta-box for disable post thumbnail ****/
// things we need: meta-key (custom field name) here:  reach_disable_thumb
// unique id of meta box. 

add_action( 'load-post.php', 'reach_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'reach_post_meta_boxes_setup' );
/* Meta box setup function. */
function reach_post_meta_boxes_setup() {

  /* Add meta boxes on the 'add_meta_boxes' hook. */
  add_action( 'add_meta_boxes', 'reach_add_post_meta_boxes' );

  /* Save post meta on the 'save_post' hook. */
  add_action( 'save_post', 'reach_save_post_meta', 10, 2 );
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function reach_add_post_meta_boxes() {

  add_meta_box(
    'reach-disable-thumb',      // Unique ID
    esc_html__( 'Hide Featured Image', 'reach' ),    // Title
    'reach_post_meta_box',   // Callback function
    'post',         // Admin page (or post type)
    'side',         // Context (normal, advanced & side)
    'default'         // Priority
  );
}

/* Display the post meta box. */
function reach_post_meta_box( $object, $box ) { ?>

  <?php wp_nonce_field( basename( __FILE__ ), 'reach_post_disable_thumb_nonce' );
   

    $reach_stored_meta = get_post_meta( $object->ID );
    //echo "all meta <pre>"; var_dump($reach_stored_meta); echo "</pre>";
    $meta_key = "reach_disable_thumb";
    $meta_value = get_post_meta( $object->ID, $meta_key, true );
    //echo "Meta value<pre>"; var_dump($reach_stored_meta); echo "</pre>";
?>

  <p>

    <input type="checkbox" name="reach-disable-thumb" id="reach-disable-thumb" value="yes" <?php if ( isset ( $reach_stored_meta['reach_disable_thumb'] ) ) checked( $reach_stored_meta['reach_disable_thumb'][0], 'yes' ); ?> />
    <label for="meta-checkbox">
        <?php _e( 'Hide featured image', 'reach' )?>
    </label>
  </p>
 
<?php } // end of reach_post_class_meta function

/* Save post meta on the 'save_post' hook. */
add_action( 'save_post', 'reach_save_post_meta', 10, 2 );
/* Meta box setup function. */
function reach_save_post_meta( $post_id, $post ) {

   /* Verify the nonce before proceeding. */
  if ( !isset( $_POST['reach_post_disable_thumb_nonce'] ) || !wp_verify_nonce( $_POST['reach_post_disable_thumb_nonce'], basename( __FILE__ ) ) )
    return $post_id;

    /* Get the post type object. */
      $post_type = get_post_type_object( $post->post_type );
    /* Check if the current user has permission to edit the post. */
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

    /* Get the posted data and sanitize it for use as an HTML class. */
    $new_meta_value = ( isset( $_POST['reach-disable-thumb'] ) ? sanitize_html_class( $_POST['reach-disable-thumb'] ) : '' );

    /* Get the meta key. */
     $meta_key = 'reach_disable_thumb';

     /* Get the meta value of the custom field key. */
    $meta_value = get_post_meta( $post_id, $meta_key, true );

    /* If a new meta value was added and there was no previous value, add it. */
    if ( $new_meta_value && '' == $meta_value )
        add_post_meta( $post_id, $meta_key, $new_meta_value, true );

    /* If the new meta value does not match the old value, update it. */
    elseif ( $new_meta_value && $new_meta_value != $meta_value )
        update_post_meta( $post_id, $meta_key, $new_meta_value );

    /* If there is no new meta value but an old value exists, delete it. */
    elseif ( '' == $new_meta_value && $meta_value )
        delete_post_meta( $post_id, $meta_key, $meta_value );
}
/**** end of meta box. ****/

/** override the blogs shortcode **/

function reach_blog_posts(  $atts, $content = null) {

    global $flatsome_opt;
  $element_id = rand();
  extract(shortcode_atts(array(
    "posts" => '8',
    "columns" => '4',
    "category" => '',
    "style" => 'text-normal',
    "auto_slide" => 'false',
    "type" => "slider", // Slider / Grid / Masonry
    "image_height" => 'auto',
    "show_date" => 'true',
    "excerpt" => 'true',
    "title" => '',
  ), $atts));

  if($type == 'masonry'){
    $style = 'text-boxed';
    $image_height = 'auto';
  }

  ob_start();
  ?>

<div class="row">
 <div class="large-12 column">
 
      <?php
                    $args = array(
                        'post_status' => 'publish',
                        'post_type' => 'post',
            'category_name' => $category,
                        'posts_per_page' => $posts
                    );

                    $recentPosts = new WP_Query( $args );

                    if ( $recentPosts->have_posts() ) { ?>
                     <ul id="id-<?php echo $element_id;?>" class="blog-posts <?php if($type == 'masonry') echo 'masonry'; ?> 
                        <?php if($type == 'slider') { ?>
                          ux-row-slider js-flickity
                          <?php if($style !== 'text-overlay') echo ' slider-nav-reveal slider-nav-push'; ?> 
                         <?php } ?>
                         large-block-grid-<?php echo $columns; ?> small-block-grid-2"
                          data-flickity-options='{ 
                              "cellAlign": "left",
                              "autoPlay" : <?php echo $auto_slide; ?>,
                              "wrapAround": true,
                              "percentPosition": true,
                              "imagesLoaded": true,
                              "pageDots": false,
                              "contain": true,
                              "selectedAttraction" : 0.05,
                          "friction": 0.6
                          }'>
                          <?php if ($title ) {
                        echo '<h2 class="reach_post_title">'.$title.'</h2>';
                    } ?>
                        <?php while ( $recentPosts->have_posts() ) : $recentPosts->the_post(); ?>

            <li class="ux-box text-center post-item ux-<?php echo $style; ?>">
                <div class="inner">
                  <div class="inner-wrap">
                  <a href="<?php the_permalink() ?>">
                    <div class="ux-box-image">
                        <div class="entry-image-attachment" style="max-height:<?php echo  $image_height; ?>;overflow:hidden;">
                      <?php the_post_thumbnail('medium'); ?>
                    </div>
                    </div><!-- .ux-box-image -->
                    <div class="ux-box-text text-vertical-center">
                        <h3 class="from_the_blog_title"><?php the_title(); ?></h3>
                        <div class="tx-div small"></div>
                          <?php if($excerpt != 'false') { ?>
                            <p class="from_the_blog_excerpt small-font show-next"><?php
                                $excerpt = get_the_excerpt();
                                echo string_limit_words($excerpt,15) . '[...]';
                            ?>
                         </p>
                       <?php } ?>
                       <?php 
                       // Show comments only if it's enabled or more than 1.
                       if(comments_open() && '0' != get_comments_number()){ ?>
                         <p class="from_the_blog_comments uppercase smallest-font">
                            <?php comments_popup_link( __( 'Leave a comment', 'flatsome' ), __( '<strong>1</strong> Comment', 'flatsome' ), __( '<strong>%</strong> Comments', 'flatsome' ) ); ?>
                         </p>
                         <?php } ?>
                        
                       </div><!-- .post_shortcode_text -->
                  </a>

                   <?php if($show_date != 'false') {?>
                          <div class="post-date">
                                <span class="post-date-day"><?php echo get_the_time('d', get_the_ID()); ?></span>
                                <span class="post-date-month"><?php echo get_the_time('M', get_the_ID()); ?></span>
                         </div>
                  <?php } ?>
                </div><!-- .inner-wrap -->
                </div><!-- .inner -->
            </li><!-- .blog-item -->
                          
                        <?php endwhile; // end of the loop. ?>
         </ul>
                    <?php

                  };
          wp_reset_query();

                    ?>

</div><!-- col -->
</div><!-- row -->

    <?php if($type == 'masonry'){ ?>
      <script>
      jQuery(document).ready(function ($) {
          imagesLoaded( document.querySelector('#id-<?php echo $element_id; ?>'), function( instance, container ) {
            var $container = $("#id-<?php echo $element_id; ?>");
            // initialize
            $container.packery({
              itemSelector: ".ux-box",
              gutter: 0,
            });
          $container.packery('layout');
        });
       });
      </script>
    <?php } ?>
  <?php
  $content = ob_get_contents();
  ob_end_clean();
  return $content;
}
