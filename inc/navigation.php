<?php
/**
 * Navigation
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/
/**
* Generate a class attribute and an AMP class attribute binding.
*
* @param string $default Default class value.
* @param string $active  Value when the state enabled.
* @param string $state   State variable to toggle based on.
* @return string HTML attributes.
*/

/* walker for primary menu sub nav */
class etcode_sublevel_walker extends Walker_Nav_Menu
{
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$output .='<button class="ouvrir-sous-menu picto"><span class="screen-reader-text">Montrer ou masquer le sous-menu</span><span class="picto-angle"><svg class="svg-icon" width="12" height="12" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 7"><path d="M3.659,1.308a1,1,0,0,1,1.682,0L8.01,5.459A1,1,0,0,1,7.168,7H1.832A1,1,0,0,1,.99,5.459Z" transform="translate(9 7) rotate(180)"></path></svg></span></button><ul class="sub-menu">';
	}
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= "</ul>";
	}
}


/**
* Navigation
*
*/
add_action( 'tha_header_bottom', 'ea_site_header', 10 );


function ea_site_header() {
	echo '<div class="header-navigation">';


		//Navigation mobile
		kasutan_mobile_nav();

		//Navigation desktop
		echo '<nav id="site-navigation" class="nav-main" aria-label="menu principal">';
			if( has_nav_menu( 'primary' ) ) {
				if( class_exists('etcode_sublevel_walker') ) {
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'walker' => new etcode_sublevel_walker,
						'container_class' => 'nav-primary'
					) );
				} else {
					wp_nav_menu( array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'walker' => new etcode_sublevel_walker,
						'container_class' => 'nav-primary'
					) );
				}

			}
			do_action('wpml_add_language_selector');
		echo '</nav>';
	echo '</div>';
}


function kasutan_mobile_nav() {
	?>
	<button class="menu-toggle hamburger hamburger--squeeze" type="button" id="menu-toggle" aria-controls="volet-navigation"  aria-label="Menu">
	<span class="hamburger-box">
		<span class="hamburger-inner"></span>
	</span>
	</button>

	<div class="volet-navigation"  id="volet-navigation">
		<?php
		if( class_exists('etcode_sublevel_walker') ) {
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_id'        => 'menu-mobile',
				'walker' => new etcode_sublevel_walker,
				'menu_class'=>'menu-mobile',
			) );
		} else {
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'menu_id'        => 'menu-mobile',
				'menu_class'=>'menu-mobile',
			) );
		}
		do_action('wpml_add_language_selector');
	echo '</div>'; //Fin volet navigation
}


/**
 * Archive Paginated Navigation
 *
 */
add_action( 'tha_content_while_after', 'kasutan_archive_paginated_navigation',20 );
function kasutan_archive_paginated_navigation() {
	if( ! is_singular() )
	the_posts_pagination();
}

