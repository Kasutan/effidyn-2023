<?php
/**
 * Archive partial
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

//On récupère une éventuelle info sur le tag html passée en $args de get_template_part
$tag='li';
if(!empty($args) && array_key_exists('tag',$args)) {
	$tag=$args['tag'];
}
$class=get_post_type();
$post_id=get_the_ID();
$titre=get_the_title($post_id);
$link=get_the_permalink($post_id);
$ruban='';
if(is_sticky($post_id)) {
	$ruban=sprintf('<div class="ruban">%s</div>',__('à la une','effidyn'));
	$class.=' sticky';
}

printf('<%s class="vignette %s">',$tag,$class);
	if(has_post_thumbnail()) {
		printf('<a href="%s" class="image">%s %s</a>',$link,get_the_post_thumbnail( $post_id, 'medium'),$ruban);
	}
	printf('<a href="%s"><h2 class="titre-item">%s</h2>',$link,$titre);
	kasutan_affiche_metas_article($post_id);	
	printf('<a class="extrait" href="%s">%s</a>',$link,get_the_excerpt($post_id));
printf('</%s>',$tag);

