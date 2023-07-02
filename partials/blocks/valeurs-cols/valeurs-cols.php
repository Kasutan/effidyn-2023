<?php 
/**
* Template pour le bloc valeurs-cols
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
$intro_section=wp_kses_post( get_field('intro_section') );
$nb_cols=esc_attr(get_field('nb_cols'));
$style=esc_attr(get_field('style'));
if($style==='decor') {
	$className.=' decor-top decor-bleu';
}
$centrer_texte=esc_attr(get_field('centrer_texte'));
if($centrer_texte) $className.=' centrer-texte';

$masquer_traits=esc_attr(get_field('masquer_traits'));
if($masquer_traits) $className.=' masquer-traits';


if(have_rows('cols')) : 

	printf('<section class="acf valeurs-cols %s nb-cols-%s">', $className,$nb_cols);

		if($titre_section) printf('<h2 class="titre-section decor-trait-simple">%s</h2>',$titre_section);
		if($intro_section) printf('<p class="intro-section">%s</p>',$intro_section);

			echo '<div class="cols">';
			while(have_rows('cols')) : the_row();
				$titre=wp_kses_post( get_sub_field('titre') );
				$sous_titre_1=wp_kses_post( get_sub_field('sous_titre_1') );
				$sous_titre_2=wp_kses_post( get_sub_field('sous_titre_2') );
				$picto_1=esc_attr(get_sub_field('picto_1'));
				$picto_2=esc_attr(get_sub_field('picto_2'));
				$texte=wp_kses_post( get_sub_field('texte') );
				

				echo '<div class="col">';
					if($titre) printf('<h3 class="titre">%s</h3>',$titre);

					if($sous_titre_1) {
						if($picto_1) {
							printf('<p class="sous-titre avec-picto"><span class ="picto">%s</span><span>%s</span></p>',wp_get_attachment_image($picto_1),$sous_titre_1);
						} else {
							printf('<p class="sous-titre">%s</p>', $sous_titre_1);
						}
					} else if($picto_1) {
						printf('<p class="sous-titre picto-seul"><span class ="picto">%s</span>',wp_get_attachment_image($picto_1));
					}
					if($sous_titre_2) {
						if($picto_2) {
							printf('<p class="sous-titre avec-picto"><span class ="picto">%s</span><span>%s</span></p>',wp_get_attachment_image($picto_2),$sous_titre_2);
						} else {
							printf('<p class="sous-titre">%s</p>', $sous_titre_2);
						}
					} else if($picto_2) {
						printf('<p class="sous-titre picto-seul"><span class ="picto">%s</span>',wp_get_attachment_image($picto_2));
					}
					
					printf('<div class="texte">%s</div>',$texte);
				echo '</div>';  // fin .col

			endwhile;
			echo '</div>';

	echo '</section>';

endif;
