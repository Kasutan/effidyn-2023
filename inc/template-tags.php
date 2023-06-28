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


	if(function_exists('get_field')) {
		$surtitres=get_field('effidyn_surtitres','options');
	//TODO adapter les options dans le groupe de champs - type groupe, textes

	}

	if(!isset($surtitres['blog']) || !empty($surtitres['blog'])) {
		$surtitres['blog']=__('Publications','effidyn');
	}

	if(!isset($surtitres['cas']) || !empty($surtitres['cas'])) {
		$surtitres['cas']=__('Références > Etude de cas','effidyn');
	}


	$titre=$surtitre="";
	$publication=false;
	if(is_single() ) {
		$post_type=get_the_post_type();
		$titre=get_the_title();
		if($post_type==='post') {
				$surtitre=$surtitres['blog'];
				$publication=true;
		} else if($post_type==='reference') {
				$surtitre=$surtitres['cas'];
		} 
	} else if (is_page()) {
		$titre=get_the_title();
		$current=get_post(get_the_ID());
		$parent=$current->post_parent; 
		if($parent) {
			$surtitre=strip_tags(get_the_title($parent));
		}
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

	//TODO archives des références, search et 404

	//si surtitre non vide ajouter une classe
	//si $publication ajouter une classe

	printf('<div class="page-banniere">');
		//decor top si !$publication
		//surtitre
		//titre
		//decor diagonale si !$publication
		//TODO inclure un div qui overflow (avec décor ou dégradé)
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
* Filtre par catégories pour les archives de blog
*
*/
function kasutan_affiche_filtre_articles() {
	$avec_pictos=function_exists('kasutan_picto_categorie');
	echo '<p class="screen-reader-text">Filtrer les actualités</p>';
	echo '<form class="filtre-archive" id="filtre-liste">';
		$terms=get_terms( array(
			'taxonomy' => 'category'
			) 
		);
		?>
		<input type="radio" id="toutes" name="filtre-categorie" value="toutes" checked>
		<label for="toutes" class="toutes">Toutes</label>
		<?php
		foreach($terms as $term) : 
			$pictos=$classe='';
			if($avec_pictos) {
				$picto_blanc=kasutan_picto_categorie($term->term_id,'blanc');
				$picto_bleu=kasutan_picto_categorie($term->term_id,'bleu');
				if($picto_blanc && $picto_bleu) {
					$pictos=sprintf('<span class="picto blanc">%s</span><span class="picto bleu">%s</span>',wp_get_attachment_image( $picto_blanc,'thumbnail'),wp_get_attachment_image( $picto_bleu,'thumbnail'));
					$classe="avec-pictos";
				}
			}
			

			printf('<input type="radio" id="%s" name="filtre-categorie" value="%s">',
				$term->slug,
				$term->slug
			);
			printf('<label for="%s" class="%s">%s %s</label>',
				$term->slug,
				$classe,
				$pictos,
				$term->name
			);
		endforeach;
		?>
		
	</form>
<?php
}

/**
* Afficher un bouton avec le même markup que Gutenberg
*
*/

function kasutan_affiche_bouton($label,$url,$classe='') {
	printf('<div class="wp-block-buttons is-content-justification-center is-layout-flex">
		<div class="wp-block-button %s">
			<a class="wp-block-button__link wp-element-button" href="%s">%s</a>
		</div>
	</div>', $classe,$url,$label);
}