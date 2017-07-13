<?php

add_filter('widget_text','do_shortcode');

//Ensure that VC styles are always loaded
add_action('joints_entry', 'blog_filler');

function blog_filler() {
	do_shortcode('[vc_row el_class="blog-filler"][vc_column][/vc_column][/vc_row]');
}

//-------Begin external scripts-------

add_action('wp_head', 'preloader');

function preloader() {
  ?>
  <link href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/preloader.css" rel="stylesheet" type="text/css">
  <?php
}

add_action('wp_footer', 'end_preloader', 50);

function end_preloader() {
  ?>
  <link href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/preloader-end.css" rel="stylesheet" type="text/css">
  <?php
}

//-------End external scripts-------

//-------Begin Global Variables------

$column_width = get_option('column_width');
$sidebar_width = get_option('sidebar_primary_width');

//-------End Global Variables------

//-------Begin Custom Options------

class Joints_Core_Custom_Options {
   /**
    * This hooks into 'customize_register' (available as of WP 3.4) and allows
    * you to add new sections and controls to the Theme Customize screen.
    */
   public static function register ( $wp_customize ) {
      $wp_customize->add_section( 'site_layout', 
         array(
            'title'       => __( 'Site Layout', 'joints' ), //Visible title of section
            'priority'    => 35, //Determines what order this appears in
            'capability'  => 'edit_theme_options', //Capability needed to tweak
            'description' => __('Set standard page layout options', 'joints'), //Descriptive tooltip
         ) 
      );
      
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
       );
       
       $wp_customize->add_setting( 'sidebar_primary_width', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
         array(
             'default'    => '4', 
             'type'       => 'option', 
             'capability' => 'edit_theme_options',
             'transport'  => 'postMessage', 
         ) 
      );       
            
      $wp_customize->add_control(
         'sidebar_primary_width_control', //Set a unique ID for the control
         array(
             'label'      => __( 'Primary Sidebar Width', 'joints' ), 
             'settings'   => 'sidebar_primary_width', 
             'priority'   => 11, 
             'section'    => 'site_layout', 
             'type' => 'number',
             'default' => '4',
             'input_attrs' => array(
                 'min' => '1',
                 'max' => '12',
             ),
             'description' => 'Set the column width of the primary sidebar.  It\'s recommended that the total column widths equal 12.',
         ) 
       );
     

      //4. We can also change built-in settings by modifying properties. For instance, let's make some stuff use live preview JS...
       /**
      $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
      $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
      $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
      $wp_customize->get_setting( 'background_color' )->transport = 'postMessage';*/
   }

   /**
    * This will output the custom WordPress settings to the live theme's WP head.
    * 
    * Used by hook: 'wp_head'
    * 
    * @see add_action('wp_head',$func)
    * @since MyTheme 1.0
    */
   public static function header_output() {
      ?>
      <!--Customizer CSS--> 
      <style type="text/css">
           <?php self::generate_css('#site-title a', 'color', 'header_textcolor', '#'); ?> 
           <?php self::generate_css('body', 'background-color', 'background_color', '#'); ?> 
           <?php self::generate_css('a', 'color', 'link_textcolor'); ?>
      </style> 
      <!--/Customizer CSS-->
      <?php
   }
   
   /**
    * This outputs the javascript needed to automate the live settings preview.
    * Also keep in mind that this function isn't necessary unless your settings 
    * are using 'transport'=>'postMessage' instead of the default 'transport'
    * => 'refresh'
    * 
    * Used by hook: 'customize_preview_init'
    * 
    * @see add_action('customize_preview_init',$func)
    * @since MyTheme 1.0
    */
   public static function live_preview() {
      wp_enqueue_script( 
           'mytheme-themecustomizer', // Give the script a unique ID
           get_template_directory_uri() . '/assets/js/theme-customizer.js', // Define the path to the JS file
           array(  'jquery', 'customize-preview' ), // Define dependencies
           '', // Define a version (optional) 
           true // Specify whether to put in footer (leave this true)
      );
   }

    /**
     * This will generate a line of CSS for use in header output. If the setting
     * ($mod_name) has no defined value, the CSS will not be output.
     * 
     * @uses get_theme_mod()
     * @param string $selector CSS selector
     * @param string $style The name of the CSS *property* to modify
     * @param string $mod_name The name of the 'theme_mod' option to fetch
     * @param string $prefix Optional. Anything that needs to be output before the CSS property
     * @param string $postfix Optional. Anything that needs to be output after the CSS property
     * @param bool $echo Optional. Whether to print directly to the page (default: true).
     * @return string Returns a single line of CSS with selectors and a property.
     * @since MyTheme 1.0
     */
    public static function generate_css( $selector, $style, $mod_name, $prefix='', $postfix='', $echo=true ) {
      $return = '';
      $mod = get_theme_mod($mod_name);
      if ( ! empty( $mod ) ) {
         $return = sprintf('%s { %s:%s; }',
            $selector,
            $style,
            $prefix.$mod.$postfix
         );
         if ( $echo ) {
            echo $return;
         }
      }
      return $return;
    }
}

//-------End Custom Options------

//-------General Functions-------
function close_element() {
	?>
	</div>
	<?php
}
function get_featured() {
	the_post_thumbnail('full');
}

//Opens a visual composer row with a column inside of the user's choice of size
function open_vc_row_wrapper($i = 12) {
    return '<div class="vc_row">
            <div class="vc_col-sm-12">
                <div class="vc_column-inner">';
}

//Opens a visual composer row and column
function close_vc_row_wrapper() {
    return '</div></div></div>';
}

//-------Begin Shortcodes------

add_shortcode('get_search_box', 'get_search_form');
add_shortcode('inline_custom_button', 'get_custom_button');
add_shortcode('joints_site_map', 'get_site_map');
add_shortcode('content_slider_controls', 'get_slider_content_controls');

function get_custom_button($atts) {
  ob_start();
  the_widget('Custom_Button', $atts);
  $output = ob_get_contents();
  ob_end_clean();
  return $output;
}
function get_site_map() {
  $primary = wp_get_nav_menu_items('primary-nav');
  $output = '<div class="vc_column_container vc_col-sm-3">';
  $i = 1;
  foreach($primary as $ind => $item) {
    if($item->menu_item_parent == 0 ) {
      if($ind != 0){
        $output .= '</div><div class="vc_column_container vc_col-sm-3">';
      } 
      $output .= '<strong>';
    }
    $output .= '<a href="' . $item->url . '">' . $item->title . '</a><br />';
    if($item->menu_item_parent == 0 ) {
      $output .= '</strong>';
    }
  }
  $output .= '</div>';
  return $output;
}
function get_slider_content_controls() {
  return do_shortcode('[inline_custom_button class="content-slide-prev content-slide-dir arrow-left arrow-left-blue" style="border-color: transparent;"]') . 
  do_shortcode('[inline_custom_button class="content-slide-next content-slide-dir arrow-left arrow-left-blue" style="border-color: transparent;"]');
}

//-------End Shortcodes-------

//-------Begin Header Functionality-------

add_action('joints_before_nav', 'get_site_logo');

function get_site_logo() {
	echo '<a href="' . get_home_url() . '" class="site-logo"><img src="' . get_site_icon_url() . '" alt="' . get_bloginfo('name') . '" /></a>';
}

	//-------Begin Intro Section-------

add_filter('intro_class', 'initialize_classes_intro', 1);
add_filter('intro_class', 'do_classes_intro', 99);

function initialize_classes_intro() {
	return array('intro');
}
function do_classes_intro($classes) {
	echo 'class="' . implode(' ', $classes) . '"';
}

add_action('joints_intro', 'open_intro', 2);
add_action('joints_intro', 'do_intro_content');
add_action('joints_intro_content', 'open_intro_content', 2);
add_action('joints_intro_content', 'get_intro_content');
add_action('joints_intro_content', 'close_intro_content', 50);
add_action('joints_intro_content', 'close_element', 50);
add_action('joints_intro', 'close_element', 50);

function open_intro() {
	?>
	<div <?php apply_filters('intro_class', 'intro') ?>>
	<?php
}
function open_intro_content() {
	?>
	<div class="intro-content">
		<div class="vc_row">
			<div class="vc_column_container vc_col-sm-6">
				<div class="vc_column-inner">
	<?php
}
function do_intro_content() {
	do_action('joints_intro_content');
}
function get_intro_content() {
	$page_for_posts_id = get_option('page_for_posts');
	$page = (is_home() || is_archive() ? $page_for_posts_id : null);
	echo //(is_front_page() ? '' : get_the_title()) .
		(function_exists('get_field') ? get_field('intro_content', $page) : '');
}
function close_intro_content() {
	?>
	</div>
		</div>
			</div>
	<?php
}

	//-------End Intro Section-------

add_action('joints_nav', 'joints_do_topbar');

function joints_do_topbar() {
	echo '<div class="top-sidebar">';
	dynamic_sidebar('top_sidebar'); 
	echo '</div>';
}

//-------End Header Functionality-------

//-------Begin Loop-------

add_action('joints_entry', 'joints_loop');

function joints_loop() {
  if (have_posts()) { 
  		while (have_posts()) { 
  			the_post();
    		if(get_post_type() === 'page') {
        		get_template_part( 'parts/loop', 'page' );
      		}
      		elseif(is_single()) {
      			get_template_part( 'parts/loop', 'single' );
      		}
      		else {
      			get_template_part( 'parts/loop', 'archive' );
      		}
		}
		if(is_archive() || is_front_page()) {
			joints_page_navi();
		}
	}
	else {
		get_template_part( 'parts/content', 'missing' );
	} 
}

//-------End Loop-------

//-------Begin Entry Header

add_filter('entry_header_class', 'initialize_entry_header_class', 1);
add_filter('entry_header_class', 'do_entry_header_class', 99);

function initialize_entry_header_class() {
	return array('entry-header');
}
function do_entry_header_class($classes) {
	echo 'class="' . implode(' ', $classes) . '"';
}

add_action('joints_entry_header', 'open_entry_header', 1);
add_action('joints_entry_header', 'close_entry_header', 99);

function open_entry_header() {
	?>
	<header <?php apply_filters('entry_header_class'); ?>>
	<?php
}
function close_entry_header() {
	?>
	</header>
	<?php
}

function get_archive_title() {
	?>
	<h2>
		<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
	<?php  
}

function get_byline() {
	get_template_part( 'parts/content', 'byline' );
}

//-------End Entry Header-------

//-------Begin Entry Content-------

add_action('joints_entry_content', 'open_entry_content', 1);
add_action('joints_entry_content', 'close_entry_content', 99);
add_action('joints_entry_content', 'the_entry_content', 9);

function open_entry_content() {
	?>
	<section class="entry-content" itemprop="articleBody">
	<?php
}
function close_entry_content() {
	?>
	</section> <!-- end article section -->
	<?php
}
function the_entry_content() {
	if(is_archive() || is_front_page()) {
		the_content('<button class="tiny">' . __( 'Read more...', 'jointswp' ) . '</button>');
	}
	else {
		the_content();
	}
}

//-------End Entry Content-------

//-------Begin Sidebar

add_action('joints_primary_sidebar', 'get_sidebar');

add_action('joints_sidebar_inner', 'get_primary_sidebar');

function get_primary_sidebar() {
	if ( is_active_sidebar( 'sidebar1' ) ) {
		dynamic_sidebar( 'sidebar1' );
	}
}

//-------End Sidebar
