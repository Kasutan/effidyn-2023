<?php
/**
 * Single Post
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

//Body class
function kasutan_single_body_class( $classes ) {
	if(!has_post_thumbnail()) {
		$classes[] = 'no-thumbnail';
	}
	return $classes;
}
add_filter( 'body_class', 'kasutan_single_body_class' );

// Bannière avec titre, sur-titre et décors
add_action( 'tha_entry_top', 'kasutan_page_banniere', 7 );



//Titre inséré avant le contenu pour mise en page grille
add_action('tha_entry_content_before', 'kasutan_single_entry_content_before');
//TODO utiliser ces hooks pour insérer extrait, métas et thumbnail
function kasutan_single_entry_content_before() {
	$post_id=get_the_ID();

	$auteurs=false;
	if(function_exists('get_field')) {
		$auteurs=wp_kses_post(get_field('auteurs'));
	}
	if(!$auteurs) {
		$auteurs=get_the_author();
	}
	$list=get_the_category_list('<span class="vir">, </span>');
	//Métas single
	echo '<div class="meta-single">';
		printf('<p class="date">%s <strong>%s</strong>',__('Publié le :','effidyn'),get_the_date('d/m/Y'));
		printf('<p class="author">%s <strong>%s</strong>',__('Par :','effidyn'),$auteurs);
		printf('<p class="cats">%s %s',__('Catégorie thématique :','effidyn'),$list);
	echo '</div>';

	//Extrait
	printf('<p class="single-extrait">%s</p>', get_the_excerpt());

	//Image

	printf('<div class="single-image">%s</div>',get_the_post_thumbnail($post_id,'large'));

}

add_action('tha_entry_content_after','kasutan_single_entry_content_after');
function kasutan_single_entry_content_after(){
	if(function_exists('kasutan_boutons_partage')) {
		kasutan_boutons_partage();
	}

	if(function_exists('kasutan_affiche_trois_articles') && function_exists('ea_first_term')) {
		$term = ea_first_term();
		$exclude=array();
		$exclude[]=get_the_ID();
		kasutan_affiche_trois_articles($term, '', $exclude,'pour-single',false) ;
	}

}


// Build the page
require get_template_directory() . '/index.php';
