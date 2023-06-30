<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/***************************************************************
	Custom Post Type : references
/***************************************************************/
function kasutan_references_post_type() {

	$labels = array(
		'name'                  => _x( 'Références', 'Post Type General Name', 'effidyn' ),
		'singular_name'         => _x( 'Référence', 'Post Type Singular Name', 'effidyn' ),
		'menu_name'             => __( 'Références', 'effidyn' ),
		'name_admin_bar'        => __( 'Références', 'effidyn' ),
		'archives'              => __( 'Archives des références', 'effidyn' ),
		'attributes'            => __( 'Item Attributes', 'effidyn' ),
		'parent_item_colon'     => __( 'Parent Item:', 'effidyn' ),
		'all_items'             => __( 'Toutes les références', 'effidyn' ),
		'add_new_item'          => __( 'Ajouter une référence', 'effidyn' ),
		'add_new'               => __( 'Ajouter', 'effidyn' ),
		'new_item'              => __( 'Nouveau référence', 'effidyn' ),
		'edit_item'             => __( 'Modifier cette référence', 'effidyn' ),
		'update_item'           => __( 'Mettre à jour cette référence', 'effidyn' ),
		'view_item'             => __( 'Voir cette référence', 'effidyn' ),
		'view_items'            => __( 'Voir les références', 'effidyn' ),
		'search_items'          => __( 'Rechercher une référence', 'effidyn' ),
		'not_found'             => __( 'Aucune référence', 'effidyn' ),
		'not_found_in_trash'    => __( 'Aucune référence dans la corbeille', 'effidyn' ),
	);
	$args = array(
		'label'                 => __( 'Références', 'effidyn' ),
		'description'           => __( 'Références', 'effidyn' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'revisions', 'custom-fields','thumbnail','editor' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 20,
		'menu_icon'             => 'dashicons-star-filled',
		'taxonomies'            => array( 'savoir-faire'),
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'show_in_rest'          => true,
	);
	register_post_type( 'reference', $args );

}
add_action( 'init', 'kasutan_references_post_type', 0 );