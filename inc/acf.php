<?php
/**
 * ACF Customizations
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

class BE_ACF_Customizations {

	public function __construct() {

		// Only allow fields to be edited on development
		if ( ! defined( 'WP_LOCAL_DEV' ) || ! WP_LOCAL_DEV ) {
			//add_filter( 'acf/settings/show_admin', '__return_false' );
		}

		// Save and sync fields.
		add_filter( 'acf/settings/save_json', array( $this, 'get_local_json_path' ) );
		add_filter( 'acf/settings/load_json', array( $this, 'add_local_json_path' ) );
		add_action( 'admin_init', array( $this, 'sync_fields_with_json' ) );

		// Register options page
		add_action( 'init', array( $this, 'register_options_page' ) );

		// Register Blocks
		add_filter( 'block_categories_all', array($this,'kasutan_block_categories'), 10, 2 );
		add_action('acf/init', array( $this, 'register_blocks' ) );

	}

	/**
	 * Define where the local JSON is saved.
	 *
	 * @return string
	 */
	public function get_local_json_path() {
		return get_template_directory() . '/acf-json';
	}

	/**
	 * Add our path for the local JSON.
	 *
	 * @param array $paths
	 *
	 * @return array
	 */
	public function add_local_json_path( $paths ) {
		$paths[] = get_template_directory() . '/acf-json';

		return $paths;
	}

	/**
	 * Automatically sync any JSON field configuration.
	 */
	public function sync_fields_with_json() {
		if ( defined( 'DOING_AJAX' ) || defined( 'DOING_CRON' ) )
			return;

		if ( ! function_exists( 'acf_get_field_groups' ) )
			return;

		$version = get_option( 'ea_acf_json_version' );

		if ( defined( 'KASUTAN_STARTER_VERSION' ) && version_compare( KASUTAN_STARTER_VERSION, $version ) ) {
			update_option( 'ea_acf_json_version', KASUTAN_STARTER_VERSION );
			$groups = acf_get_field_groups();

			if ( empty( $groups ) )
				return;

			$sync = array();
			foreach ( $groups as $group ) {
				$local    = acf_maybe_get( $group, 'local', false );
				$modified = acf_maybe_get( $group, 'modified', 0 );
				$private  = acf_maybe_get( $group, 'private', false );

				if ( $local !== 'json' || $private ) {
					// ignore DB / PHP / private field groups
					continue;
				}

				if ( ! $group['ID'] ) {
					$sync[ $group['key'] ] = $group;
				} elseif ( $modified && $modified > get_post_modified_time( 'U', true, $group['ID'], true ) ) {
					$sync[ $group['key'] ] = $group;
				}
			}

			if ( empty( $sync ) )
				return;

			foreach ( $sync as $key => $v ) {
				if ( acf_have_local_fields( $key ) ) {
					$sync[ $key ]['fields'] = acf_get_local_fields( $key );
				}
				acf_import_field_group( $sync[ $key ] );
			}
		}
	}

	/**
	 * Register Options Page
	 *
	 */
	function register_options_page() {
		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_page(array(
				'page_title' 	=> 'Réglages du site Effidyn',
				'menu_title'	=> 'Réglages du site Effidyn',
				'menu_slug' 	=> 'site-settings',
				'capability'	=> 'edit_posts',
				'position' 		=> '2.5',
				'redirect'		=> false,
				'update_button' => 'Mettre à jour',
				'updated_message' => 'Réglages du site mis à jour',
				'capability' => 'manage_options',

			));
		}
	}

	/**
	* Enregistre une catégorie de blocs liée au thème
	*
	*/
	function kasutan_block_categories( $categories, $post ) {
		return array_merge(
			array(
				array(
					'slug' => 'effidyn',
					'title' => 'effidyn',
					'icon'  => 'calculator',
				),
			),
			$categories
		);
	}

	function helper_register_block_type($slug,$titre,$description,$icon='calculator',$js=false,$keywords=[], $multiple=true ){
		$keywords_from_slug=explode('-',$slug);
		$keywords=array_merge($keywords,$keywords_from_slug, array('effidyn'));
		$args=[
			'name'            => $slug,
			'title'           => $titre,
			'description'     => $description,
			'render_template' => 'partials/blocks/'.$slug.'/'.$slug.'.php',
			'enqueue_style' => get_stylesheet_directory_uri() . '/partials/blocks/'.$slug.'/'.$slug.'.css',
			'category'        => 'effidyn',
			'icon'            => $icon, 
			'mode'			=> "edit",
			'supports' => array( 
				'mode' => false,
				'align'=>false,
				'multiple'=>$multiple,
				'anchor' => true,
			),
			'keywords'        => $keywords
		];
		if($js) {
			$args['enqueue_script']=get_stylesheet_directory_uri() . '/js/min/'.$slug.'/'.$slug.'.js';
		}
		acf_register_block_type( $args);
	}
	

	/**
	 * Register Blocks
	 * @link https://www.billerickson.net/building-gutenberg-block-acf/#register-block
	 *
	 * Categories: common, formatting, layout, widgets, embed
	 * Dashicons: https://developer.wordpress.org/resource/dashicons/
	 * ACF Settings: https://www.advancedcustomfields.com/resources/acf_register_block/
	 */
	function register_blocks() {

		if( ! function_exists('acf_register_block_type') )
			return;


		/*********Bloc accueil-services ***************/
		$this->helper_register_block_type( 
			'accueil-services',
			'Bloc services pour page Accueil',
			'Section avec intro, titre et grille de services avec pictos.',
			'calculator', 
			false, 
			array('accueil', 'service')
		);
		/*********Bloc accueil-services ***************/
		$this->helper_register_block_type( 
			'accueil-valeurs',
			'Bloc valeurs pour page Accueil',
			'Section pleine largeur sur fond bleu avec titre, décor, 3 valeurs et un lien.',
			'calculator', 
			false, 
			array('accueil', 'valeur')
		);

		/*********Bloc blog ***************/
		$this->helper_register_block_type( 
			'blog',
			'Bloc publications pour page Accueil',
			'Section avec titre principal et les trois derniers articles publiés sur le blog.',
			'calculator', 
			false, 
			array('blog', 'article', 'accueil','publication','actualité')
		);


		/*********Bloc carrousel de logos ***************/
		$this->helper_register_block_type( 
			'carrousel',
			'Bloc carrousel de logos de clients',
			'Section avec titre, carrousel de logos des clients et un lien.',
			'calculator', 
			true, //besoin de JS pour le carrousel
			array('logo', 'accueil','carrousel','client')
		);


		/*********Bloc savoir-faire-etapes ***************/
		$this->helper_register_block_type( 
			'savoir-faire-etapes',
			'Bloc étapes pour page Savoir-Faire',
			'Section avec intro et 3 étapes numérotées',
			'calculator', 
			false, 
			array('savoir-faire', 'etape')
		);

		/*********Bloc savoir-faire-services ***************/
		$this->helper_register_block_type( 
			'savoir-faire-services',
			'Bloc services pour page Savoir-Faire',
			'Section avec titre et grille de services avec pictos.',
			'calculator', 
			false, 
			array('savoir-faire', 'service')
		);

		/*********Bloc savoir-faire-cols ***************/
		$this->helper_register_block_type( 
			'savoir-faire-cols',
			'Bloc colonnes pour pages enfant de Savoir-Faire',
			'Section avec titre et deux colonnes encadrées (mobile) séparées par un trait bleu (desktop).',
			'calculator', 
			false, 
			array('savoir-faire', 'col','colonne')
		);

		/*********Bloc consultants ***************/
		$this->helper_register_block_type( 
			'consultants',
			'Bloc consultants pour page Nous rejoindre',
			'Section avec liste de portraits de consultants. Pour chaque personne : photo, nom, titre et témoignage.',
			'calculator', 
			false, 
			array('consultant', 'recrutement','rejoindre')
		);
		
		/*********Bloc valeurs-cols ***************/
		$this->helper_register_block_type( 
			'valeurs-cols',
			'Bloc colonnes pour pages Qui sommes nous et Notre approche',
			'Section avec titre et deux ou trois blocs (mobile) / colonnes séparées par un trait bleu (desktop). Variante avec des pictos',
			'calculator', 
			false, 
			array('valeurs', 'col','colonne','qui sommes-nous','approche')
		);

		/*********Bloc équipe ***************/
		$this->helper_register_block_type( 
			'equipe',
			'Bloc équipe pour page Qui sommes-nous',
			'Section avec liste de portraits de dirigeants. Pour chaque personne : photo, nom, diplômes, bio et lien LinkedIn.',
			'calculator', 
			false, 
			array('equipe', 'qui sommes-nous','dirigeant')
		);

		/*********Bloc candidature ***************/
		$this->helper_register_block_type( 
			'candidature',
			'Bloc candidature pour page Nous rejoindre',
			'Section pleine largeur avec fond bleu, titre et formulaire.',
			'calculator', 
			false,
			array('candidature', 'formulaire','rejoindre')
		);

		/*********Bloc logos-grille ***************/
		$this->helper_register_block_type( 
			'logos-grille',
			'Bloc grille de logos pour page Nos clients',
			'Section avec grille de logos.',
			'calculator', 
			false, 
			array('logo', 'client','ref','grille')
		);

		/*********Bloc temoignages ***************/
		$this->helper_register_block_type( 
			'temoignages',
			'Bloc temoignages pour page Nos clients',
			'Section avec titre et témoignages clients présentés sur 2 colonnes en desktop.',
			'calculator', 
			false, 
			array('client', 'temoignage')
		);

		/*********Bloc etudes-cas ***************/
		$this->helper_register_block_type( 
			'etudes-cas',
			'Bloc toutes les études de cas',
			'Section avec grille de vignettes et pagination.',
			'calculator', 
			false, //le JS pour la pagination est celui du blog
			array('etude', 'cas','archive')
		);

		/*********Bloc etudes-cas-tax ***************/
		$this->helper_register_block_type( 
			'etudes-cas-tax',
			'Bloc études de cas d\'un savoir-faire',
			'Section pleine largeur avec fond bleu, titre et grille de vignettes.',
			'calculator', 
			false,
			array('etude', 'cas','savoir','faire')
		);

	}
}
new BE_ACF_Customizations();
