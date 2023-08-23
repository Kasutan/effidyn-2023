<?php 
/**
* Template pour le bloc accueil-valeurs
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/


if(array_key_exists('className',$block)) {
	$className=esc_attr($block["className"]);
} else $className='';


$titre_section=wp_kses_post( get_field('titre_section') );
$hero=esc_attr(get_field('effidyn_deco_hero_top','options'));
$lien=esc_url( get_field('lien') );

printf('<section class="acf accueil-valeurs decor-top %s">', $className);
	if($hero) {
		printf('<div class="decor-hero-top" aria-hidden="true">%s</div>',wp_get_attachment_image($hero, 'banniere')); 
	}
	echo '<div class="decor-hero-bottom"></div>';


	printf('<h2 class="titre-section decor-trait-simple">%s</h2>',$titre_section);
	if(have_rows('valeurs')) :
		echo '<ul class="valeurs">';
		while(have_rows('valeurs')) : the_row();
			$titre=wp_kses_post( get_sub_field('titre') );
			$texte=wp_kses_post( get_sub_field('texte') );
			echo '<li class="valeur">';
				printf('<p class="titre">%s</p><p class="texte">%s</p>',$titre,$texte);
			echo '</li>';
		endwhile;
		echo '</ul>';
	endif;
	if($lien && function_exists('kasutan_affiche_bouton')) {
		kasutan_affiche_bouton($lien,'','is-style-outline'); //label par défaut
	}

echo '</section>';