<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function kasutan_reference_get_term($post_id) {
	$term=false;
	if(function_exists('ea_first_term')) {
		$term=ea_first_term(array('taxonomy'=>'savoir-faire','field'=>null,'post_id'=>$post_id));
	}
	return $term;
}

function kasutan_affiche_etude($post_id,$contexte) {
	$titre=get_the_title($post_id);
	$link=get_the_permalink($post_id);
	$term=kasutan_reference_get_term($post_id);

	

	printf('<li class="vignette reference">');
		printf('<a href="%s" class="image">%s</a>',$link,get_the_post_thumbnail( $post_id, 'medium'));
		echo '<div class="texte">';
			if($contexte==='archive') {
				printf('<a href="%s"><h2 class="titre-item">',$link);
				if($term) printf('<span class="term">%s</span>',$term->name);
				printf('<span class="nom">%s</span></h2></a>',$titre);
			} else {
				printf('<a href="%s"><h3 class="titre-item">%s</h3></a>',$link,$titre);
			}
		
			printf('<a class="extrait" href="%s">%s</a>',$link,get_the_excerpt($post_id));

			if(function_exists('kasutan_affiche_bouton')) {
				kasutan_affiche_bouton($link);

			}
		echo '</div>';
	echo '</a></li>';
}