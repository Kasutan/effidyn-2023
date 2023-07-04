<?php 
/**
* Template pour le bloc temoignages
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/

if(!function_exists('kasutan_decor_traits')) {
	return;
}
if(array_key_exists('className',$block)) {
	$className=esc_attr($block["className"]);
} else $className='';


$titre_section=wp_kses_post( get_field('titre_section') );
if(have_rows('temoignages_1') || have_rows('temoignages_2')) :

	printf('<section class="acf temoignages decor-top decor-bleu %s">', $className);

		printf('<h2 class="titre-section decor-trait-simple">%s</h2>',$titre_section);
		
		echo '<div class="cols-temoignages">';
		if(have_rows('temoignages_1')) :
			echo '<div class="col col-1">';
			while(have_rows('temoignages_1')) : the_row();
				$nom=wp_kses_post( get_sub_field('nom') );
				$entreprise=wp_kses_post( get_sub_field('entreprise') );
				$role=wp_kses_post( get_sub_field('role') );
				$texte=wp_kses_post( get_sub_field('texte') );
				printf('<div class="temoignage"><p class="nom">%s</p><p class="entreprise">%s</p><p class="role">%s</p><div class="texte">%s</div></div>',$nom, $entreprise, $role,$texte);
			endwhile;
			echo '</div>';
		endif;
		if(have_rows('temoignages_2')) :
			echo '<div class="col col-2">';
			while(have_rows('temoignages_2')) : the_row();
				$nom=wp_kses_post( get_sub_field('nom') );
				$entreprise=wp_kses_post( get_sub_field('entreprise') );
				$role=wp_kses_post( get_sub_field('role') );
				$texte=wp_kses_post( get_sub_field('texte') );
				printf('<div class="temoignage"><p class="nom">%s</p><p class="entreprise">%s</p><p class="role">%s</p><div class="texte">%s</div></div>',$nom, $entreprise, $role,$texte);
			endwhile;
			echo '</div>';
		endif;
		echo '</div>';


	echo '</section>';
endif;
