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
		printf('<a href="%s" class="image"><div class="image-wrap">%s</div></a>',$link,get_the_post_thumbnail( $post_id, 'medium'));
		echo '<div class="texte">';
			if($contexte==='archive') {
				printf('<a href="%s"><h2 class="titre-item">',$link);
				if($term) printf('<span class="term">%s</span>',$term->name);
				printf('<span class="nom">%s</span></h2></a>',$titre);
			} else {
				printf('<a href="%s"><h3 class="titre-item">%s</h3></a>',$link,$titre);
			}
		
			printf('<p class="extrait">%s</p>',get_the_excerpt($post_id));

			if(function_exists('kasutan_affiche_bouton')) {
				kasutan_affiche_bouton($link);

			}
		echo '</div>';
	echo '</a></li>';
}

function kasutan_affiche_etudes_pour_tax($term, $titre_section='', $exclude=array(),$className='') {

	$args=array(
		'post_type' => 'reference',
		'posts_per_page' => '-1',
		'orderby' => 'date',
		'order' => 'DESC',
		'tax_query' => array(
			array(
				'taxonomy' => 'savoir-faire',
				'terms' => $term->term_id,
			),
		),
	);
	if(!empty($exclude)) {
		$args['post__not_in']=$exclude;
	}

	$articles=new WP_Query($args);

	if(!$articles->have_posts(  )) {
		return;
	}

	$count=$articles->post_count;

	if(!$titre_section) {
		if($count> 1) {
			$titre_section=__('Autres études de cas :','effidyn');
		} else {
			$titre_section=__('Autre étude de cas :','effidyn');
		}
		
	}

	printf('<section class="etudes-related decor-top decor-bleu %s">', $className);

		printf('<h2 class="titre-section">%s <span class="term">%s</span></h2>',$titre_section,$term->name);
			echo '<ul class="references">';

			while ( $articles->have_posts() ) {
				$articles->the_post();
				kasutan_affiche_etude(get_the_ID(),'savoir-faire');
			}
			echo '</ul>';
		wp_reset_postdata();


	echo '</section>';
}