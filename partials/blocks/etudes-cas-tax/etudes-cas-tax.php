<?php 
/**
* Template pour le bloc etudes-cas-tax
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/


if(!function_exists('kasutan_affiche_etudes_pour_tax')) {
	return;
}

if(array_key_exists('className',$block)) {
	$className=esc_attr($block["className"]);
} else $className='';

$term=get_field('term');
$titre_section=wp_kses_post(get_field('titre_section'));

kasutan_affiche_etudes_pour_tax($term,$titre_section,array(),$className);