<?php 
/**
* Template pour le bloc savoir-faire-cols
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

if(have_rows('cols')) : 

	printf('<section class="acf savoir-faire-cols %s">', $className);

		if($titre_section) printf('<h2 class="titre-section">%s</h2>',$titre_section);
			echo '<div class="cols">';
			while(have_rows('cols')) : the_row();
				$titre=wp_kses_post( get_sub_field('titre') );
				$texte=wp_kses_post( get_sub_field('texte') );
				printf('<div class="col"><h3 class="titre decor-trait-simple">%s</h3><p class="texte">%s</p></div>',$titre,$texte);

			endwhile;
			echo '</div>';

	echo '</section>';

endif;
