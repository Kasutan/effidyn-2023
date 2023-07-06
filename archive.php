<?php
/**
 * Archive
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/


/**
 * Body Class
 *
 */
function ea_archive_body_class( $classes ) {
	$classes[] = 'archive';
	return $classes;
}
add_filter( 'body_class', 'ea_archive_body_class' );

/**
 * Archive Header
 *
 */
add_action( 'tha_content_while_before', 'ea_archive_header' );
function ea_archive_header() {




	echo '<header class="entry-header">';
	do_action ('ea_archive_header_before' );
	do_action ('ea_archive_header_after' );
	echo '</header>';

	echo '<div class="container">';

		if(is_home()) {
			if(function_exists('kasutan_affiche_top_article')) {
				kasutan_affiche_top_article();
			}
			if(function_exists('kasutan_affiche_liste_cats')) {
				kasutan_affiche_liste_cats();
			}
			echo '<div id="archive-avec-pagination">';
				echo '<ul class="loop list">';
		} else { 
			echo '<ul class="loop">';
		}

}


// Bannière avec titre, sur-titre et décors
add_action( 'ea_archive_header_before', 'kasutan_page_banniere', 7 );

// Fermer balise loop
add_action( 'tha_content_while_after', 'ea_archive_while_after',10 );
function ea_archive_while_after() {
	echo '</ul> <!--end .loop-->';
	if(is_home()) {
		echo '<ul class="pagination"></ul>';
		echo '</div>'; // end #archive-avec-pagination
	}
	echo '</div>'; //end .container
}
// Build the page
require get_template_directory() . '/index.php';
