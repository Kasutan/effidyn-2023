<?php 
/**
* Template pour le bloc Blog
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/


if(!function_exists('kasutan_affiche_trois_articles')) {
	return;
}

if(array_key_exists('className',$block)) {
	$className=esc_attr($block["className"]);
} else $className='';


$titre_section=wp_kses_post( get_field('titre_section') );
$label_bouton=wp_kses_post( get_field('label_bouton') );

//On réutilise la fonction définie pour la section related au bas des pages single post
kasutan_affiche_trois_articles(false, $titre_section, array(),'pour-bloc',$label_bouton) ;