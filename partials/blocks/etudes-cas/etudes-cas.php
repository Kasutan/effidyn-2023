<?php 
/**
* Template pour le bloc etudes-cas
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

$pagination_desktop=esc_attr(get_field('pagination_desktop'));
$pagination_mobile=esc_attr(get_field('pagination_mobile'));

$articles=new WP_Query(array(
	'post_type' => 'reference',
	'posts_per_page' => -1,
	'orderby' => 'date',
	'order' => 'DESC'
));

wp_enqueue_script( 'effidyn-listjs', get_template_directory_uri() . '/lib/list/list.min.js', array(), '1.0', true );
wp_enqueue_script( 'effidyn-pagination', get_template_directory_uri() . '/js/min/pagination.js', array('jquery','effidyn-listjs'), filemtime( get_template_directory() . '/js/min/pagination.js'), true );

printf('<section class="acf etudes-cas %s" id="archive-avec-pagination" data-pag-mobile="%s" data-pag-desktop="%s">', $className,$pagination_mobile,$pagination_desktop);
	if(!$articles->have_posts(  )) {
		printf('<p>%s</p>',__('Aucune étude de cas','effidyn'));
		return;
	}


	echo '<ul class="loop list ">';

	while ( $articles->have_posts() ) {
		$articles->the_post();
		kasutan_affiche_etude(get_the_ID(),'archive');
	}
	echo '</ul>';
	wp_reset_postdata();

	echo '<ul class="pagination"></ul>';


echo '</section>';
	