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


		/*********Bloc banniere ***************/
		$this->helper_register_block_type( 
			'banniere',
			'Bloc image bannière',
			'Section avec une image de fond et un texte sur fond coloré semi-opaque la page d\'accueil.',
			'calculator', 
			false,
			array( 'banniere', 'accueil')
		);


		/*********Bloc blog ***************/
		$this->helper_register_block_type( 
			'blog',
			'Bloc blog',
			'Section avec titre principal et les trois derniers articles publiés sur le blog. Carrousel sur petits écrans.',
			'calculator', 
			true, //JS pour carrousel mobile
			array('blog', 'article', 'accueil')
		);


		/*********Bloc carrousel de logos ***************/
		$this->helper_register_block_type( 
			'carrousel',
			'Bloc carrousel de logos des marques',
			'Section avec titre et carrousel de logos des marques',
			'calculator', 
			true, //besoin de JS pour le carrousel
			array('logo', 'marque','carrousel')
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
			'Bloc colonnes pour section Nos valeurs dans page Qui sommes nous',
			'Section avec titre et trois blocs (mobile) / colonnes séparées par un trait bleu (desktop).',
			'calculator', 
			false, 
			array('valeurs', 'col','colonne','qui sommes-nous')
		);

	}
}
new BE_ACF_Customizations();
