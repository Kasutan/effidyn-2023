<?php
/**
 * Single Reference
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

// Bannière avec titre, sur-titre et décors
add_action( 'tha_entry_top', 'kasutan_page_banniere', 7 );



//Avant the content : titre, extrait et thumbnail
add_action('tha_entry_content_before', 'kasutan_single_ref_entry_content_before');
//TODO utiliser ces hooks pour insérer extrait, métas et thumbnail
function kasutan_single_ref_entry_content_before() {
	$post_id=get_the_ID();

	$image=false;
	if(function_exists('get_field')) {
		$image=esc_attr(get_field('banniere'));
	}

	//titre 
	printf('<h2 class="single-title decor-trait-simple">%s</h2>',get_the_title());

	//Extrait
	printf('<p class="single-extrait intro">%s</p>', get_the_excerpt());

	//Image

	if($image) {
		printf('<div class="single-image large">%s</div>',wp_get_attachment_image($image, 'large'));
	} else if (has_post_thumbnail($post_id)) {
		printf('<div class="single-image">%s</div>',get_the_post_thumbnail($post_id,'large'));
	}

}

//Après the_content : autres études de cas pour le même savoir-faire
add_action('tha_entry_content_after','kasutan_single_ref_entry_content_after');
function kasutan_single_ref_entry_content_after(){

	if(!function_exists('kasutan_affiche_etudes_pour_tax') || !function_exists('kasutan_reference_get_term')) {
		return;
	}
	$post_id=get_the_ID();
	$term=kasutan_reference_get_term($post_id);
	if($term) {
		$exclude=[$post_id];
		kasutan_affiche_etudes_pour_tax($term,'',$exclude,'');
	}

}


// Build the page
require get_template_directory() . '/index.php';
