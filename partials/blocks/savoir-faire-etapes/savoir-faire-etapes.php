<?php 
/**
* Template pour le bloc savoir-faire-etapes
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


$intro=wp_kses_post( get_field('intro') );

printf('<section class="acf savoir-faire-etapes %s">', $className);

	printf('<p class="intro">%s</p>',$intro);
	if(have_rows('etapes')) :
		echo '<ol class="etapes">';
		$n=1;
		while(have_rows('etapes')) : the_row();
			$titre=wp_kses_post( get_sub_field('titre') );
			$texte=wp_kses_post( get_sub_field('texte') );
			printf('<li class="etape"><span class="num">%s</span><p class="titre">%s</p>%s<div class="texte">%s</div>',$n,$titre,kasutan_decor_traits(),$texte);
			$n++;
		endwhile;
		echo '</ol>';
	endif;


echo '</section>';
	