<?php 
/**
* Template pour le bloc savoir-faire-services
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

printf('<section class="acf savoir-faire-services decor-top decor-bleu %s">', $className);

	printf('<h2 class="titre-section">%s</h2>',$titre_section);
	if(have_rows('services')) :
		echo '<ul class="services">';
		while(have_rows('services')) : the_row();
			$image=esc_attr(get_sub_field('picto'));
			$titre=wp_kses_post( get_sub_field('titre') );
			$texte=wp_kses_post( get_sub_field('texte') );
			$lien=esc_url( get_sub_field('lien') );
			echo '<li class="service">';
				printf('<div class="picto">%s</div><p class="titre">%s</p><p class="texte">%s</p>',wp_get_attachment_image($image),$titre,$texte);

				if($lien && function_exists('kasutan_affiche_bouton')) {
					kasutan_affiche_bouton($lien); //label par défaut
				}
			echo '</li>';
		endwhile;
		echo '</ul>';
	endif;


echo '</section>';
	