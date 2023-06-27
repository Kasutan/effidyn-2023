<?php


/**
 * Formulaire newsletter + menus + logo Footer
 *
 */
add_action( 'tha_footer_top', 'kasutan_main_footer' );
function kasutan_main_footer() {

	$logo=$coord=false;
	$titres=array();
	if(function_exists('get_field')) {
		$logo=esc_attr(get_field('effidyn_footer_logo','option'));		
		$coord=wp_kses_post(get_field('effidyn_footer_coordonnees','option'));
		$titres[2]=wp_kses_post(get_field('effidyn_footer_titre_2','option'));
		$titres[3]=wp_kses_post(get_field('effidyn_footer_titre_3','option'));
		$titres[4]=wp_kses_post(get_field('effidyn_footer_titre_4','option'));
	}

	$posts=get_posts(array('numberposts'=>3));

	/*

	echo '<a class="backtotop" href="#haut-page"><span class="screen-reader-text">Retour en haut</span>
		<svg xmlns="http://www.w3.org/2000/svg" width="40" height="23.327" viewBox="0 0 40 23.327"><path d="M106.945,51.7l-2-2a1.26,1.26,0,0,0-1.844,0L87.345,65.444,71.593,49.692a1.26,1.26,0,0,0-1.844,0l-2,2a1.26,1.26,0,0,0,0,1.844L86.423,72.218a1.261,1.261,0,0,0,1.843,0L106.945,53.54a1.263,1.263,0,0,0,0-1.844Z" transform="translate(-67.345 -49.291)" fill="#fff"></path></svg>
	</a>';
*/

	echo '<div class="main-footer decor-top"><div class="colonnes-footer">';
	

	for($i=1;$i<=4;$i++) {
		printf('<div class="col-%s col">',$i);

		

		if(isset($titres[$i])) {
			printf('<p class="titre-widget">%s</p>',$titres[$i]);
		}

		if( has_nav_menu( 'footer-'.$i ) ) {
			wp_nav_menu( array( 'theme_location' => 'footer-'.$i, 'menu_id' => 'footer-'.$i, 'container_class' => 'nav-footer' ) );
		}

		if($i==1) {
			if($logo) {
				$url=apply_filters( 'wpml_home_url', get_option( 'home' ) );
				printf('<a href="%s" class="logo-footer">%s</a>',$url,wp_get_attachment_image($logo,'medium'));
			}

			if($coord) {
				printf('<div class="coord">%s</div>',$coord);
			}
		}

		if($i==4 && !empty($posts)) {
			echo '<ul class="liste-posts">';
			foreach($posts as $post) {
				printf('<li><a href="%s">%s</a></li>',$post->post_permalink,$post->post_title);
			}
			echo '</ul>';
		}

		

		echo '</div>'; //fin de la colonne

	}

	echo '</div>'; //fin colonnes-footer

	
	
	echo '</div>'; //fin main-footer

	//3 bandes colorées en décor
	?>
		<div class="decor-footer" aria-hidden="true">
			<div class="rect rect-1"></div>
			<div class="rect rect-2"></div>
			<div class="rect rect-3"></div>
		</div>

	<?php
}


/**
* Copyright et liens légaux
*
*/
add_action( 'tha_footer_bottom', 'kasutan_copyright' );
function kasutan_copyright() {
	


	echo '<div class="bottom-footer">';
		printf('<span class="copyright">%s %s</span>',esc_html__('&copy; Tous droits réservés','effidyn'),get_option('blogname'));
		
		if( has_nav_menu( 'footer-copyright') ) {
			wp_nav_menu( array( 'theme_location' => 'footer-copyright', 'menu_id' => 'footer-copyright', 'container_class' => 'inline-footer' ) );
		}
	echo '</div>';
}