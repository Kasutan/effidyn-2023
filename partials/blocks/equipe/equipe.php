<?php 
/**
* Template pour le bloc equipe
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


if(have_rows('dirigeants')) :

printf('<section class="acf liste-dirigeants %s">', $className);
	if($titre_section) printf('<h2 class="titre-section decor-trait-simple">%s</h2>',$titre_section);
	if($intro_section) printf('<p class="intro-section">%s</p>',$intro_section);

		echo '<ul class="dirigeants">';
		while(have_rows('dirigeants')) : the_row();
			$image=esc_attr(get_sub_field('image'));
			$nom=wp_kses_post( get_sub_field('nom') );
			$diplomes=wp_kses_post( get_sub_field('diplomes') );
			$texte=wp_kses_post( get_sub_field('texte') );
			$lien=esc_url(get_sub_field('lien'));

			echo '<li class="dirigeant">';
				printf('
					<div class="portrait"><div class="image">%s</div><a class="picto" href="%s" title="%s %s" target="_blank" rel="noopener noreferrer"></a></div>
					<p class="nom">%s</p>
					<p class="diplomes">%s</p>
					<div class="texte">%s</div>',
					wp_get_attachment_image($image),
					$lien,
					__('Consulter le profil LinkedIn de'),
					$nom,
					$nom,
					$diplomes,
					$texte
				);

			echo '</li>';
		endwhile;
		echo '</ul>';


echo '</section>';
endif;
