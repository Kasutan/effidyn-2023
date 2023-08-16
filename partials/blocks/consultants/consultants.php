<?php 
/**
* Template pour le bloc consultants
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/


if(array_key_exists('className',$block)) {
	$className=esc_attr($block["className"]);
} else $className='';


if(have_rows('consultants')) :

printf('<section class="acf liste-consultants %s">', $className);

		echo '<ul class="consultants">';
		while(have_rows('consultants')) : the_row();
			$image=esc_attr(get_sub_field('image'));
			$nom=wp_kses_post( get_sub_field('nom') );
			$titre=wp_kses_post( get_sub_field('titre') );
			$texte=wp_kses_post( get_sub_field('texte') );
			echo '<li class="consultant">';
				printf('
					<div class="portrait"><div class="image">%s</div><div class="picto" aria-hidden="true">%s</div></div>
					<p class="nom">%s</p>
					<p class="titre">%s</p>
					<div class="texte">%s</div>',
					wp_get_attachment_image($image),
					kasutan_picto(array('icon'=>'picto-temoignage','size'=>35)),
					$nom,
					$titre,
					$texte
				);

			echo '</li>';
		endwhile;
		echo '</ul>';


echo '</section>';
endif;
