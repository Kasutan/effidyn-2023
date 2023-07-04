<?php 
/**
* Template pour le bloc logos-grille
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/


if(array_key_exists('className',$block)) {
	$className=esc_attr($block["className"]);
} else $className='';


$logos=get_field('logos'); //champ ACF galerie
if(!empty($logos)) :
	printf('<section class="acf logos-grille %s">', $className);
		for($i=1;$i<=3;$i++) {
			printf('<div class="ligne ligne-%s" aria-hidden="true"></div>',$i);
		}
		echo '<ul class="logos">';
			foreach($logos as $logo) {
				printf('<li class="logo">%s</li>',wp_get_attachment_image($logo,'medium'));
			}
		echo '</ul>';

	echo '</section>';
endif;