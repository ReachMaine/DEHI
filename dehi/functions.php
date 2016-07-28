<?php
	require_once(get_stylesheet_directory().'/custom/language.php'); 
	require_once(get_stylesheet_directory().'/custom/flat.php'); 
	require_once(get_stylesheet_directory().'/custom/cf7.php'); 
	// require_once(get_stylesheet_directory().'/custom/woocommerce.php'); 


	add_action('after_setup_theme', ea_setup);
	/**  ea_setup
	*  init stuff that we have to init after the main theme is setup.
	* 
	*/
	function ea_setup() {
	 /* do stuff ehre. */
	 /* add_filter( 'image_size_names_choose', 'ea_custom_sizes' ); // choose image size in media drop down. */
		//reach_woo_setup();
	 
	}
	add_image_size('reach_featured_image', 750, 350, false);
	function ea_custom_sizes( $sizes ) {
	    return array_merge( $sizes, array(
	        'reach_featured_image' => __( 'Reach Blog Featured' ),
	    ) );
	}
	add_filter('widget_text', 'do_shortcode'); // make text widget do shortcodes....

	/* image size for facebook */
	add_image_size( 'facebook_share', 470, 246, true );
	add_image_size('facebook_share_vert', 246, 470, true);
	add_filter('wpseo_opengraph_image_size', 'mysite_opengraph_image_size');
	function mysite_opengraph_image_size($val) {
		return 'facebook_share';
	}
	
		// contact form 7 fallback for date field 
	add_filter( 'wpcf7_support_html5_fallback', '__return_true' );
	
	/*****  change the login screen logo ****/
	function my_login_logo() { ?>
		<style type="text/css">
			body.login div#login h1 a {
				background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/admin-login.png);
				padding-bottom: 30px;
				background-size: contain;
				margin-left: 0px;
				margin-bottom: 0px;
				margin-right: 0px;
				width: 100%;
			}
		</style>
	<?php }
	add_action( 'login_enqueue_scripts', 'my_login_logo' );

	add_action( 'login_footer', 'reach_login_branding' );
	function reach_login_branding() {
		$outstring = "";
		$outstring .= '<p style="text-align:center;">';
		$outstring .= 	'<img src="'.get_stylesheet_directory_uri().'/images/reach-favicon.png'.'">';
		$outstring .= 		'R<i style="color: #f58220">EA</i>CH Maine';
		$outstring .= '</p>';
		echo $outstring;
	}



	// filter to wrap embedded videos (youtube) in div ss.t. we can style it
	add_filter('embed_oembed_html', 'wrap_embed_with_div', 10, 3);
	function wrap_embed_with_div($html, $url, $attr) {
        return '<div class="ea-responsive-container">'.$html.'</div>';
}


// add widget area for foundation pages.
 register_sidebar( array(
    'name'          => 'Foundation Header',
    'id'            => 'dehi-foundation-header',
    'description' 	=> 'Above Foundation Pages content',
    'before_widget' => '<div id="dehi-foundation" class="dehi-foundation-header widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widget-title shop-sidebar">',
    'after_title'   => '</h3>',
  ) );
?>
