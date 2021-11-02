<?php
/**
 * Child theme functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme"s functions.php file. The child theme"s functions.php
 * file is included before the parent theme"s file, so the child theme
 * functions would be used.
 *
 * Text Domain: oceanwp
 * @link http://codex.wordpress.org/Plugin_API
 *
 */

/**
 * Load the parent style.css file
 *
 * @link http://codex.wordpress.org/Child_Themes
 */

 // Utilisation feuille de style thème enfant
function oceanwp_child_enqueue_parent_style() {
	$theme   = wp_get_theme( "OceanWP" );
	$version = $theme->get( "Version" );
	wp_enqueue_style( "child-style", get_stylesheet_directory_uri() . "/style.css", array( "oceanwp-style" ), $version );
	
}
add_action( "wp_enqueue_scripts", "oceanwp_child_enqueue_parent_style" );

// Utilisation feuille de style thème enfant pour la partie page de connexion
function my_login_stylesheet() {
	wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/login/login-style.css' );
   }
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );
   

// Utilisation fichier JS personnel	  
function add_my_script() {
	if(is_single())
	{
    wp_register_script('chalet_single', get_stylesheet_directory_uri().'/js/chalet_single.js', array('jquery'));
    wp_enqueue_script('chalet_single');
	}
}
add_action( 'wp_enqueue_scripts', 'add_my_script' );


// Désactive les attribut srcset des images 
function disable_wp_responsive_images() {
	return 1;
}
add_filter('max_srcset_image_width', 'disable_wp_responsive_images');


add_filter( 'wp_get_attachment_image_attributes', function( $attr )
{
    if( isset( $attr['sizes'] ) )
        unset( $attr['sizes'] );
    if( isset( $attr['srcset'] ) )
        unset( $attr['srcset'] );
    return $attr;
 }, PHP_INT_MAX );
add_filter( 'wp_calculate_image_sizes', '__return_empty_array',  PHP_INT_MAX );
add_filter( 'wp_calculate_image_srcset', '__return_empty_array', PHP_INT_MAX );
remove_filter( 'the_content', 'wp_make_content_images_responsive' );

// Shortcode récupération du titre d'un post
function page_title_sc(){
	return ucwords(get_the_title());
	}
add_shortcode( 'page_title', 'page_title_sc' );


// Remplacement du mauvais code HTML généré par certains plugins
function callback($buffer) {      
	if (!is_admin() && !is_customize_preview())
	{
    $buffer = str_replace("<style>.mfp-hide{display: none;}</style>", "", $buffer);
	$buffer = str_replace("<h2 class=\"screen-reader-text\"> </h2>", "", $buffer);
	$buffer = str_replace(".es-widget > div:not(.es-map-property-layout-horizontal, .es-map-property-layout-vertical) { background: #f0f0f0!important }", "", $buffer);
	$buffer = str_replace("<article id=\"post", "<article id=\"a_post", $buffer);
	$buffer = str_replace("<div class=\"wpcf7-response-output\" aria-hidden=\"true\"></div></form></div></p></div>", "<div class=\"wpcf7-response-output\" aria-hidden=\"true\"></div></form></div></div>", $buffer);
	$buffer = str_replace("<style>.es-gallery br{display: none;}</style>", "", $buffer);
	$buffer = str_replace("<style>","<!--", $buffer);
	$buffer = str_replace(".es-slideshow__wrap, .widget_es_property_slideshow {
        min-width: 0;
        min-height: 0;
    }
</style>", "-->", $buffer);
	$buffer = str_replace("type='text/css'","", $buffer);
	$buffer = str_replace("type=\"text/css\"","", $buffer);
	$buffer = str_replace("frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\"","", $buffer);
	if(is_single())
	{
		$buffer = str_replace("<h2>","", $buffer);
		$buffer = str_replace(" </h2>","", $buffer);
	}
	
	}
    return $buffer; 
}
function buffer_start() { ob_start("callback"); } 
function buffer_end() { ob_end_flush(); }
add_action('after_setup_theme', 'buffer_start');
add_action('shutdown', 'buffer_end');


// Redirection des catégories du plugin
function remove_category( $string, $type ) { 
	if ( $type != 'single' && $type == 'category' && ( strpos( $string, 'category' ) !== false ) ) { 
		$url_without_category = str_replace( "/es_category/", "/", $string ); 
		return trailingslashit( $url_without_category ); 
	} 
	return  $string ; 
} 
add_filter( 'user_trailingslashit', 'remove_category', 100, 2);


// Suppression des menus pour les roles Editor etc...
add_action( 'admin_init', function () {
	$user = wp_get_current_user();
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        if ( !in_array( 'administrator', $user->roles ) ) {
    
			remove_menu_page( 'index.php' );
			remove_menu_page( 'separator1' );
			remove_menu_page( 'edit.php' );
			remove_menu_page( 'upload.php' );
			remove_menu_page( 'edit.php?post_type=page' );
			//remove_menu_page( 'es_dashboard' );
			remove_menu_page( 'edit-comments.php' );
			remove_menu_page( 'wpcf7' );
			remove_menu_page( 'separator-elementor' );
			remove_menu_page( 'elementor' );
			remove_menu_page( 'edit.php?post_type=elementor_library' );
			remove_menu_page( 'separator2' );
			remove_menu_page( 'themes.php' );
			remove_menu_page( 'plugins.php' );
			remove_menu_page( 'users.php' );
			remove_menu_page( 'tools.php' );
			remove_menu_page( 'options-general.php' );
			remove_menu_page( 'seopress-option' );
			remove_menu_page( 'separator-last' );
			remove_menu_page( 'oceanwp-panel' );
			remove_menu_page( 'Wordfence' );
			remove_menu_page( 'cfdb7-list.php' );
			remove_menu_page( 'w3tc_dashboard' );
		}
	}

}, PHP_INT_MAX );

// Suppression des menus Admin bar pour les roles Editor etc...
function mytheme_admin_bar_render() {
    global $wp_admin_bar;
	$user = wp_get_current_user();
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        if ( !in_array( 'administrator', $user->roles ) ) {
    		$wp_admin_bar->remove_menu('comments');
			$wp_admin_bar->remove_node('new-content');
		}
	}
}
add_action( 'wp_before_admin_bar_render', 'mytheme_admin_bar_render' );

// Titre des articles en minuscule pour police Dr Sugiyama
function titre_minuscule( $title, $id = null ) {
    if ( is_single() || is_singular() ) {
        return strtolower($title);
    }
    return $title;
}
add_filter( 'the_title', 'titre_minuscule', 10, 2 );