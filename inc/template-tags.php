<?php
/**
 * Template Tags
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/



/**
 * Entry Category
 *
 */
function ea_entry_category($contexte='archive') {
	$post_type=get_post_type();
	$term=false;
	if($post_type==='post') {
		$term = ea_first_term();
	}
	if( !empty( $term ) && ! is_wp_error( $term ) )
		if($contexte==='archive') {
			$picto=kasutan_picto_categorie($term->term_id,'blanc');
			if($picto) {
				printf('<div class="picto-cat">%s</div>',wp_get_attachment_image( $picto,'thumbnail'));
			}
			//pour le filtre
			printf('<span class="categorie screen-reader-text">%s</span>',$term->slug);
		} else {
			//contexte single
			
				$name=$term->name;
			
			echo '<p class="entry-category h1 entry-title">' . $name . '</p>';
		}
		
}


/**
* Picto associé à une catégorie
*
*/
function kasutan_picto_categorie($term_id,$couleur="blanc") {
	if(!function_exists('get_field')) {
		return false;
	}
	if($couleur=='blanc') {
		$champ='effidyn_picto';
	} else {
		$champ='effidyn_picto_'.$couleur;
	}
	return esc_attr(get_field($champ,'category_'.$term_id));

}

/**
 * Post Summary Title
 *
 */
function ea_post_summary_title() {
	global $wp_query;
	$tag = ( is_singular() || -1 === $wp_query->current_post ) ? 'h3' : 'h2';
	echo '<' . $tag . ' class="post-summary__title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></' . $tag . '>';
}

/**
 * Post Summary Image
 *
 */
function ea_post_summary_image( $size = 'thumbnail' ) {
	/*On cherche une image à afficher*/ 
	$image_id=get_theme_mod( 'custom_logo' ); //défaut : le logo du site
	$classe='contain';
	if(has_post_thumbnail()) {
		$image_id=get_post_thumbnail_id();
		$classe='';
	} else if(function_exists('get_field')) {
		$banniere=get_field('effidyn_page_banniere');
		if($banniere) {
			$image_id=$banniere;
			$classe='';
		} else {
			$logo_footer=get_field('effidyn_logo_footer','option');
			if($logo_footer) {
				$image_id=$logo_footer;
				$classe='contain has-bleu-background-color';
			}
		}
	}

	printf('<a class="post-summary__image %s" href="%s" tabindex="-1" aria-hidden="true" >%s</a>',
		$classe,
		get_permalink(),
		wp_get_attachment_image( $image_id, $size )
	);
}

/**
 * Entry Author
 *
 */
function ea_entry_author() {
	$id = get_the_author_meta( 'ID' );
	echo '<p class="entry-author"><a href="' . get_author_posts_url( $id ) . '" aria-hidden="true" tabindex="-1">' . get_avatar( $id, 40 ) . '</a><em>by</em> <a href="' . get_author_posts_url( $id ) . '">' . get_the_author() . '</a></p>';
}

/**
* Affiche le fil d'ariane.
*/
function kasutan_fil_ariane() {

	//On n'affiche pas le fil d'ariane sur la page d'accueil
	if(is_front_page()) {
		return;
	}

	$post_type=get_post_type();

	echo '<p class="fil-ariane">';

	
	//Pour tous les contenus : afficher en premier le lien vers l'accueil du site
	$accueil=get_option('page_on_front');
	printf('<a href="%s">%s</a><span class="sep">></span>',
		get_the_permalink( $accueil),
		strip_tags(get_the_title($accueil))
	);
	


	//Afficher la page des actualités pour les articles (single ou archive de catégorie ou archive des articles ou archive de tag)
	if ( (is_single() && 'post' === $post_type) || is_category() || is_tag() ) :
		//l'ID de la page est stockée dans les options du site
		$actus=get_option('page_for_posts'); 
		if($actus) :
			printf('<a href="%s">%s</a><span class="sep">></span>',
				get_the_permalink( $actus),
				strip_tags(get_the_title($actus))
			);
		endif;
		
		//Ajouter la catégorie d'article pour les posts single
		/*
		if(is_single()) {
			$term=ea_first_term();
			if(!empty($term)) {
				printf('<a href="%s?filtre_cat=%s">%s</a><span class="sep">></span>',
				get_the_permalink( $actus),
					$term->slug,
					$term->name
				);
			}
		}*/
	endif;


	//Afficher le titre de la page courante
	if(is_page()) : 
		//Afficher le titre de la page parente s'il y en a une, non cliquable
		$current=get_post(get_the_ID());
		$parent=$current->post_parent; 
		if($parent) :
			printf('<span class="parent">%s</span><span class="sep">></span><span class="current">%s</span>',
				strip_tags(get_the_title($parent)),
				strip_tags(get_the_title())
			);
		else :
			printf('<span class="current">%s</span>',
				strip_tags(get_the_title())
			);
		endif;
	elseif(is_single()):
		printf('<span class="current">%s</span>',
			strip_tags(get_the_title())
		);
	elseif (is_category()) :  //archives catégories d'articles
		echo '<span class="current">'.strip_tags(single_cat_title( '', false )).'</span>';
	elseif (is_tag()) :  //archives tags d'articles
		echo '<span class="current">'.strip_tags(single_tag_title( '', false )).'</span>';
	elseif (is_home()) :
		echo '<span class="current">Actualités</span>';
	elseif (is_search()) :
		echo '<span class="current">Recherche : '.get_search_query().'</span>';
	elseif (is_404()) :
		echo '<span class="current">Page introuvable</span>';

	endif;

	//Fermer la balise du fil d'ariane
	echo '</p>';

}


/**
* Affiche le titre des pages ordinaires
*
*/
function kasutan_page_titre() {
	$masquer=false;
	$classe="entry-title";
	$titre=get_the_title();
	if(function_exists('get_field') && esc_attr(get_field('effidyn_masquer_titre'))==='oui') {
		$masquer=true;
	}
	if(is_front_page(  )) {
		$masquer=true;
	}
	if($masquer) {
		$classe.=" screen-reader-text";
	}
	printf('<h1 class="%s">%s</h1>',$classe,$titre);
}

/**
* Image banniere pour les pages ordinaires
*
*/
//TODO inclure le nom de la page parente s'il y en a une en sur-titre -> Voir fil ariane
function kasutan_page_banniere($page_id=false,$use_defaut=false) {
	if(is_front_page(  )) {
		return;
	}

	$surtitres=array();
	$hero=false;
	if(function_exists('get_field')) {
		$surtitres=get_field('effidyn_surtitres','options');
		$hero=esc_attr(get_field('effidyn_deco_hero_top','options'));
	}

	if(!isset($surtitres['blog']) || empty($surtitres['blog'])) {
		$surtitres['blog']=__('Publications','effidyn');
	}

	if(!isset($surtitres['cas']) || empty($surtitres['cas'])) {
		$surtitres['cas']=__('Références > Etude de cas','effidyn');
	}


	$titre=$surtitre="";
	$publication=false;
	if(is_single() ) {
		$post_type=get_post_type();
		$titre=get_the_title();
		if($post_type==='post') {
				$surtitre=$surtitres['blog'];
				$publication=true;
		} else if($post_type==='reference') {
				$surtitre=$surtitres['cas'];
				if(function_exists('kasutan_reference_get_term')) {
					$term=kasutan_reference_get_term(get_the_ID());
					if($term) {
						$titre=sprintf('%s <span class="screen-reader-text"> : %s</span>', $term->name,$titre);
					}
				}
		} 
	} if (is_search()) {
		$titre=__('Recherche :','effidyn').' '.get_search_query();
	} elseif (is_404()) {
		$titre= __('Page introuvable :','effidyn');
	} else if (is_page()) {
		$titre=get_the_title();
		$current=get_post(get_the_ID());
		$parent=$current->post_parent; 
		if($parent) {
			$surtitre=strip_tags(get_the_title($parent));
		}

		//Note : l'archive des références est une page enfant ordinaire, les archives de custom tax aussi
	} else if(is_category()) {
		$surtitre=$surtitres['blog'];
		$titre=strip_tags(single_cat_title( '', false ));
		$publication=true;

	} else if( is_tag() ) {
		$surtitre=$surtitres['blog'];
		$titre=strip_tags(single_tag_title( '', false ));
		$publication=true;

	} elseif (is_home()) {
		$titre=$surtitres['blog'];
		$publication=true;
	}

	$class='';
	if(!empty($surtitre)) {
		$class='avec_surtitre'; //TODO : supprimer si non utilisé
	}
	if($publication) {
		$class.=' publication';
	}


	printf('<div class="page-banniere %s">',$class);
	
	echo '<div class="fond-banniere">';
	//div qui overflow (avec décors ou dégradé)
		if(!$publication) {
			if($hero) {
				printf('<div class="decor-hero-top" aria-hidden="true">%s</div>',wp_get_attachment_image($hero, 'banniere')); //TODO ajuster taille
			}
			echo '<div class="decor-hero-bottom"></div>';	
		}
	echo '</div>';

	if($surtitre) printf('<span class="surtitre">%s</span>',$surtitre);
	printf('<h1 class="titre">%s</h1>',$titre);
	echo '</div>';
	
}

/**
* Image banniere pour les actus + utilisée aussi pour la recherche
*
*/
function kasutan_actus_banniere() {
	if(!function_exists('get_field')) {
		return;
	}


	if(is_single()) {
		//On est sur un post single, on vérifie s'il a sa propre image bannière
		$image_id=esc_attr(get_field('effidyn_banniere_image'));
		if(!$image_id) {
			//si le post n'a pas d'image bannière on utilise l'image par défaut
			$image_id=esc_attr(get_field('effidyn_bg_image','option'));//image par défaut
		}

		kasutan_page_banniere(get_the_ID());
		return;
	} 

	if(is_search()) {
		
		kasutan_page_banniere(false,true);
		return;
	}

	//On est sur une page d'archive, on affiche la bannière de la page des actualités
	$actus=get_option('page_for_posts'); 

	kasutan_page_banniere($actus);
}

/**
* Image mise en avant
*
*/
function kasutan_affiche_thumbnail_dans_contenu() {
	if(has_post_thumbnail()) {
		echo '<div class="thumbnail">';
			the_post_thumbnail( 'large');
		echo '</div>';
	}
}

/**
* Liste des catégories pour les archives de blog
*
*/
function kasutan_affiche_liste_cats() {
	$terms=get_terms( array(
		'taxonomy' => 'category'
		) 
	);

	echo '<nav class="nav-cat">';
		printf('<p class="titre-nav h3">%s</p>',__('Accès thématiques','effidyn'));
		echo '<ul class="cats-links">';
	
			foreach($terms as $term) : 

				printf('<li class="wp-block-button">
						<a class="wp-block-button__link wp-element-button" href="%s">%s (%s)</a>
						</li>', 
						get_term_link($term, 'category'),
						$term->name,
						$term->count
				);
				
			endforeach;
		echo '</ul>';
	echo '</nav>';
	
}

/**
* Afficher le premier article de la page blog
*
*/
function kasutan_affiche_top_article() {
	$articles=new WP_Query(array(
		'post_type' => 'post',
		'posts_per_page' => 1,
		'orderby' => 'date',
		'order' => 'DESC'
	));
	if(!$articles->have_posts(  )) {
		return;
	}
	$n=1;
	while ( $articles->have_posts() && $n<=1) {
		$articles->the_post();
		$post_id=get_the_ID();
		$link=get_the_permalink();
		echo '<div class="top-post" style="position:relative">';
			printf('<a class="image" href="%s"><div class="image-wrap">',$link);
				echo get_the_post_thumbnail($post_id,'large');
				echo '</div>';
				if(is_sticky($post_id)) {
					printf('<div class="ruban">%s</div>',__('à la une','effidyn'));
				}
			echo '</a>';
			echo '<div class="col-texte">';
				printf('<h2 class="h3 titre-item"><a href="%s">%s</a></h2>',$link,get_the_title());
				kasutan_affiche_metas_article($post_id);
				printf('<a class="extrait" href="%s">%s</a>',$link,get_the_excerpt());
			echo '</div>';
		echo '</div>';
		$n++;
	}
	wp_reset_postdata();

}
/**
* Afficher les métas dans les vignettes article
*
*/
function kasutan_affiche_metas_article($post_id) {
	$date=get_the_date('d/m/Y',$post_id);
	$list=get_the_category_list('<span class="vir">, </span>', '', $post_id);
	echo '<p class="meta">';
		printf('<span class="date">%s <span class="sep">|</span></span>',$date);
		printf('<span class="cats">%s</span>',$list);
	echo '</p>';

}

/**
* Afficher un bouton avec le même markup que Gutenberg
*
*/

function kasutan_affiche_bouton($url,$label='',$classe='') {
	if(empty($label)) {
		$label=__('En savoir +','effidyn');
	}
	printf('<div class="wp-block-buttons is-content-justification-center is-layout-flex">
		<div class="wp-block-button %s">
			<a class="wp-block-button__link wp-element-button" href="%s">%s</a>
		</div>
	</div>', $classe,$url,$label);
}


/***
 * Insérer le HTML pour 3 petits traits sous un titre
 */

function kasutan_decor_traits() {
	return '<div class="decor-traits" aria-hidden="true">
	<div class="rect rect-1"></div>
	<div class="rect rect-2"></div>
	<div class="rect rect-3"></div>
</div>';
}

/**
* Boutons de partage pour un single
* simplesharingbuttons.com
*/
function kasutan_boutons_partage() {
	$lien=urlencode(get_the_permalink());
	$titre=urlencode(get_the_title());

	$canaux=['mail','facebook','twitter','linkedin']; //dans l'ordre où les liens seront affichés
	$urls=array();

	$urls['facebook']='https://www.facebook.com/sharer/sharer.php?u='.$lien.'&quote='.$titre;

	$urls['twitter']='https://twitter.com/intent/tweet?source='.$lien.'&text='.$titre;

	$urls['linkedin']="http://www.linkedin.com/shareArticle?mini=true&url=".$lien.'&title='.$titre;

	$urls['mail']='mailto:?subject='.$titre.'&body='.$lien;

	$labels=array();
	$labels['facebook']=__('Partager sur Facebook','effidyn');
	$labels['twitter']=__('Partager sur Twitter','effidyn');
	$labels['linkedin']=__('Partager sur LinkedIn','effidyn');
	$labels['mail']=__('Partager par email','effidyn');

	$titre=false;
	if(function_exists('get_field')) {
		$titre=wp_kses_post(get_field('effidyn_titre_partage','option'));
	}
	if(!$titre) {
		$titre=__('Partager cet article','effidyn');
	}
	echo '<div class="partage">';
		printf('<p class="titre-partage"><span class="texte">%s</span></p>',$titre);
		echo '<ul class="liens-partage">';
			foreach($canaux as $canal) {
				printf('<li><a href="%s" class="canal %s" title="%s" target="_blank" rel="noopener noreferrer"></a></li>',$urls[$canal],$canal,$labels[$canal]);
			}
		echo '</ul>';
	echo '</div>';
}

/****
 * Section avec 3 posts (pour related en bas des single ou pour bloc accueil)
 */


function kasutan_affiche_trois_articles($term, $titre_section='', $exclude=array(),$className='',$label_bouton='') {

	$args=array(
		'posts_per_page' => '3',
		'orderby' => 'date',
		'order' => 'DESC',
	);
	if($term) {
		$args['tax_query']= array(
			array(
				'taxonomy' => 'category',
				'terms' => $term->term_id,
			),
		);
	}
	if(!empty($exclude)) {
		$args['post__not_in']=$exclude;
	}

	$articles=new WP_Query($args);

	if(!$articles->have_posts(  )) {
		return;
	}

	$count=$articles->post_count;

	if(!$titre_section && $term && function_exists('get_field')) {
		$titre_section=sprintf('%s <span class="term">%s</span>',
			wp_kses_post(get_field('effidyn_titre_related','option')),
			$term->name
		);
	}

	printf('<section class="posts-related %s">', $className);

		printf('<h2 class="titre-section">%s</h2>',$titre_section);
			echo '<ul class="posts">';
			$n=1;
			//forcer loop à s'arrêter à  (même s'il y a un sticky post)
			while ( $articles->have_posts() && $n<=3) {
				$articles->the_post();
				get_template_part( 'partials/archive');
				$n++;
			}
			echo '</ul>';
		wp_reset_postdata();

		if($label_bouton) {
			$url=get_permalink( get_option( 'page_for_posts' ) );
			//TODO filtre WPML ?
			kasutan_affiche_bouton($url,$label_bouton);
		}


	echo '</section>';
}