<?php 
/* languages customizations  
- add require_once(get_stylesheet_directory().'/custom/language.php'); to functions.php to make this work.
*/
	if ( !function_exists('eai_change_theme_text') ){
		function eai_change_theme_text( $translated_text, $text, $domain ) {
			 /* if ( is_singular() ) { */
			 	$theme_text_domain = 'flatsome';
			    switch ( $translated_text ) {
					case 'Category Archives: %s' :
		                $translated_text = __( '%s',  $domain  );
		                break; 
					case  'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.':
		            	$translated_text = __( 'This entry was posted in %1$s.',  $domain  );
		            	break;
		            /* case 'Category' :
		                $translated_text = __( '',  $domain  );
		                break; */
		            /*case 'Type here...':
		            	$translated_text = __( 'Search...', $theme_text_domain  );
		            	break;
		            case 'BLOG CATEGORIES':
		            	$translated_text = __( 'Found in',  $theme_text_domain );
		            	break;
		            case 'Share this post:':
		            	$translated_text = __('Share', $theme_text_domain);
		            	break;
			        case 'Select treatment' :
		                $translated_text = __( 'Select Speciality', $theme_text_domain);
		                break;
		            case 'treatments':
		            	$translated_text = __( 'Specialty', $theme_text_domain);
		            	break;
		            case 'Doctors':
		            	$translated_text = __( 'Providers', $theme_text_domain);
		            	break; */
		            case 'Back to services':
		            	$translated_text = __( 'All Our Services',  $theme_text_domain  );
		        }
		    /* } */

	    	return $translated_text;
		}
		add_filter( 'gettext', 'eai_change_theme_text', 20, 3 );
	}

?>