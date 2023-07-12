<?php 
/**
* Template pour le bloc candidature
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/


if(array_key_exists('className',$block)) {
	$className=esc_attr($block["className"]);
} else $className='';


$titre=wp_kses_post( get_field('titre') );
$shortcode=get_field('shortcode');

printf('<section class="acf candidature decor-top decor-bleu %s">', $className);

	printf('<h2 class="titre-section">%s</h2>',$titre);
	echo do_shortcode($shortcode);


echo '</section>';
	