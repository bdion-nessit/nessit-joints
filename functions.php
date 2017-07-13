<?php
// Theme support options
require_once(get_template_directory().'/assets/functions/theme-support.php'); 

// WP Head and other cleanup functions
require_once(get_template_directory().'/assets/functions/cleanup.php'); 

// Register scripts and stylesheets
require_once(get_template_directory().'/assets/functions/enqueue-scripts.php'); 

// Register custom menus and menu walkers
require_once(get_template_directory().'/assets/functions/menu.php'); 

// Register sidebars/widget areas
require_once(get_template_directory().'/assets/functions/sidebar.php'); 

// Makes WordPress comments suck less
require_once(get_template_directory().'/assets/functions/comments.php'); 

// Replace 'older/newer' post links with numbered navigation
require_once(get_template_directory().'/assets/functions/page-navi.php'); 

// Adds support for multiple languages
require_once(get_template_directory().'/assets/translation/translation.php'); 


// Remove 4.2 Emoji Support
// require_once(get_template_directory().'/assets/functions/disable-emoji.php'); 

// Adds site styles to the WordPress editor
//require_once(get_template_directory().'/assets/functions/editor-styles.php'); 

// Related post function - no need to rely on plugins
// require_once(get_template_directory().'/assets/functions/related-posts.php'); 

// Use this as a template for custom post types
// require_once(get_template_directory().'/assets/functions/custom-post-type.php');

// Customize the WordPress login menu
// require_once(get_template_directory().'/assets/functions/login.php'); 

// Customize the WordPress admin
// require_once(get_template_directory().'/assets/functions/admin.php'); 

//-------Begin required/included files-------

//get core theme functions
//Includes standard content, structure, and shortcode functions
require_once(get_stylesheet_directory() . '/assets/functions/core.php');

//add sidebars to theme
require_once(get_stylesheet_directory() . '/widget_areas.php');

//add custom button widget to theme
require_once(get_stylesheet_directory() . '/button_widget.php');

//add custom VC widgets and functions
require_once(get_stylesheet_directory() . '/vc_custom.php');

require_once(get_stylesheet_directory() . '/pagination.php');

//-------End required/included files-------

//-------Begin external scripts-------

//add general custom scripts 
add_action('wp_head', 'get_theme_scripts');

function get_theme_scripts() {
  echo '<script type="text/javascript" src="' . get_stylesheet_directory_uri() . '/assets/js/theme_scripts.js"></script>';
}

/**
add_action('wp_head', 'get_typekit');

function get_typekit() {
  echo '<script src="https://use.typekit.net/meu8bxo.js"></script>
  <script>try{Typekit.load({ async: true });}catch(e){}</script>';
}
*/

add_action('wp_enqueue_scripts', 'enqueue_blog_load_more');
add_action( 'wp_ajax_blog_load_more', 'do_blog_load_more' );
add_action( 'wp_ajax_nopriv_blog_load_more', 'do_blog_load_more' );

function enqueue_blog_load_more() {
  wp_enqueue_script( 'blog_load_more_ajax', get_stylesheet_directory_uri() . '/assets/js/pagination_ajax.js', array('jquery'), '1.0', true );

  wp_localize_script( 'blog_load_more', 'ajax_admin_url', array(
    'ajax_url' => admin_url( 'admin-ajax.php' )
  ));
}

//-------End external scripts-------

//-------Begin Shortcodes------


//-------End Shortcodes-------

//-------Begin Global Variables------

$vc_custom_styles = "";

//-------End Global Variables-------

//-------Begin Custom Options------

class Joints_Custom_Options extends Joints_Core_Custom_Options {
   /**
    * This hooks into 'customize_register' (available as of WP 3.4) and allows
    * you to add new sections and controls to the Theme Customize screen.
    * 
    * Note: To enable instant preview, we have to actually write a bit of custom
    * javascript. See live_preview() for more.
    *  
    * @see add_action('customize_register',$func)
    * @param \WP_Customize_Manager $wp_customize
    * @link http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
    * @since MyTheme 1.0
    */
    
    function __construct(){

         $this->register();
     }
   public static function register ( $wp_customize ) {
       //Example for adding section
      /**$wp_customize->add_section( 'site_layout', 
         array(
            'title'       => __( 'Site Layout', 'joints' ), //Visible title of section
            'priority'    => 35, //Determines what order this appears in
            'capability'  => 'edit_theme_options', //Capability needed to tweak
            'description' => __('Set standard page layout options', 'joints'), //Descriptive tooltip
         ) 
      );*/
      /** Example for adding custom setting and control
      $wp_customize->add_setting( 'column_width', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
         array(
             'default'    => '', 
             'type'       => 'option', 
             'capability' => 'edit_theme_options',
             'transport'  => 'postMessage', 
             'default' => '8',
         ) 
      );       
            
      $wp_customize->add_control(
         'column_width_control', //Set a unique ID for the control
         array(
             'label'      => __( 'Main Column Width', 'joints' ), 
             'settings'   => 'column_width', 
             'priority'   => 10, 
             'section'    => 'site_layout', 
             'type' => 'number',
             'input_attrs' => array(
                 'min' => '1',
                 'max' => '12',
             ),
             'description' => 'Set the column width of the main content on pages with sidebars.  It\'s recommended that the total column widths equal 12.',
         ) 
       );*/
       
       parent::register($wp_customize);

   }

}

// Setup the Theme Customizer settings and controls...
add_action( 'customize_register' , array( 'Joints_Custom_Options' , 'register' ) );

// Output custom CSS to live site
add_action( 'wp_head' , array( 'Joints_Custom_Options' , 'header_output' ) );


// Enqueue live preview javascript in Theme Customizer admin screen
add_action( 'customize_preview_init' , array( 'Joints_Custom_Options' , 'live_preview' ) );

//-------End Custom Options------


//Code to create custom post type

/** add_action( 'init', 'create_slide_type' );
function create_slide_type() {
  register_post_type( 'intro_slide',
    array(
      'labels' => array(
        'name' => __( 'Intro Slides' ),
        'singular_name' => __( 'Intro Slide' )
      ),
      'public' => true,
      'has_archive' => true,
    )
  );
}*/

add_action('joints_intro_content', 'page_breadcrumbs');

function page_breadcrumbs() {
	global $post;
	if(is_front_page()) {
		return;
	}
	echo '<div class="page-breadcrumbs">';
	$breadcrumbs = array();
	$breadcrumbs[] = get_the_title();
	$ancestors = get_post_ancestors($post);
	foreach($ancestors as $ancestor) {
		$breadcrumbs[] = '<a href="' . get_permalink($ancestor) . '">' . get_the_title($ancestor) . '</a>';
	}
	$breadcrumbs = array_reverse($breadcrumbs);
	echo implode(' \\ ', $breadcrumbs);
	echo '</div>';
}

//Modifies nav
//Sets primary nav to Foundation dropdown by default
add_action('joints_nav', 'call_nav_filter', 3);

function call_nav_filter() {
  if ( is_admin() ) {
        return;
    }
    add_filter('wp_nav_menu_items', 'custom_nav_filter', 1, 2);
}

function custom_nav_filter($items, $args) {
  if($args->menu->slug === 'primary-nav') {
    $args->items_wrap = '<ul id="%1$s" class="%2$s horizontal dropdown menu" data-dropdown-menu>%3$s</ul>';
    $items = preg_replace('/sub\-menu/', 'sub-menu menu vertical', $items);
  }
  return $items;
}
add_action('wp_footer', 'do_vc_custom_styles');

function do_vc_custom_styles() {
	global $vc_custom_styles;
	echo '<style>' .
		$vc_custom_styles . 
	'</style>';
}


