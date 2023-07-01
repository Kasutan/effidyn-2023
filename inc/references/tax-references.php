<?php
if ( ! defined( 'ABSPATH' ) ) exit;


/***************************************************************
	Custom Taxonomy Savoir-faire pour les references
/***************************************************************/

add_action( 'init', 'kasutan_savoir_faire_tag', 0 );
function kasutan_savoir_faire_tag() {
	// Labels part for the GUI
	$labels = array(
		'name' => _x( 'Savoir-faire', 'taxonomy general name' ),
		'singular_name' => _x( 'Savoir-faire', 'taxonomy singular name' ),
		'menu_name' => __( 'Savoir-faire' ),
	); 
	register_taxonomy('savoir-faire','reference',array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true, 
		'show_admin_column' => true,
		'query_var' => true,
		'public' => false, //pas de pages archives pour cette custom tax (on place juste un bandeau au pas de la page enfant de "Savoir-faire" concernée)
		'show_in_rest' => true
	));
}
