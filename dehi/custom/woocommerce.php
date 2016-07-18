<?php 
/* woocommerce */
	add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

	function woo_remove_product_tabs( $tabs ) {

		// unset( $tabs['description'] );      	// Remove the description tab
		// unset( $tabs['reviews'] ); 			// Remove the reviews tab
		unset( $tabs['additional_information'] );  	// Remove the additional information tab

		return $tabs;

	}
	// remove sku from product details.
	add_filter( 'wc_product_sku_enabled', '__return_false' );
	
// if want to remove ordering from product archive page.
// flatsome uses it's own actions for ordering & filtering, so need to do after_theme setup. 
	function reach_woo_setup() {
		// remove "showing all 10 results" & default sorting
		// old -  remove_action( 'ux_woocommerce_navigate_products', 'woocommerce_result_count', 20 );
 		// old -  remove_action( 'ux_woocommerce_navigate_products', 'woocommerce_catalog_ordering', 30 );
		/* remove the sorting & results at top of category */
		remove_action( 'flatsome_shop_category_nav_right', 'woocommerce_result_count', 20 );
		remove_action( 'flatsome_shop_category_nav_right', 'woocommerce_catalog_ordering', 30 );		
	}
