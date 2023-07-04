<?php 
/**
* Template pour le bloc etudes-cas-tax
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/


if(!function_exists('kasutan_affiche_etude')) {
	return;
}

if(array_key_exists('className',$block)) {
	$className=esc_attr($block["className"]);
} else $className='';

$term=get_field('term');
$titre_section=wp_kses_post(get_field('titre_section'));

$articles=new WP_Query(array(
	'post_type' => 'reference',
	'posts_per_page' => '-1',
	'orderby' => 'date',
	'order' => 'DESC',
	'tax_query' => array(
		array(
			'taxonomy' => 'savoir-faire',
			'terms' => $term->term_id,
		),
	),
));

if(!$articles->have_posts(  )) {
	return;
}

printf('<section class="acf etudes-cas-tax decor-top decor-bleu %s">', $className);

	printf('<h2 class="titre-section">%s <span class="term">%s</span></h2>',$titre_section,$term->name);
	echo '<ul class="references">';

	while ( $articles->have_posts() ) {
		$articles->the_post();
		kasutan_affiche_etude(get_the_ID(),'savoir-faire');
	}
	echo '</ul>';
	wp_reset_postdata();


echo '</section>';
	