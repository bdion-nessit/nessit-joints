<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Custom Theme' );
define( 'CHILD_THEME_URL', 'http://www.paracletewebdesign.com/' );
define( 'CHILD_THEME_VERSION', '2.1.2' );

//* Enqueue Google Fonts ... or don't
/*
add_action( 'wp_enqueue_scripts', 'genesis_sample_google_fonts' );
function genesis_sample_google_fonts() {

	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:300,400,700', array(), CHILD_THEME_VERSION );

}
*/
//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );







//* Customize the credits .... or remove them
/*
add_filter( 'genesis_footer_creds_text', 'sp_footer_creds_text' );
function sp_footer_creds_text() {
	echo '<div class="creds"><p>';
	echo 'Copyright &copy; ';
	echo date('Y');
	//echo '  &nbsp;|&nbsp; <a href="' . get_site_url() . '/credits/">Credits</a> &nbsp;|&nbsp; All Rights Reserved &nbsp;|&nbsp; <a href="http://paracletewebdesign.com/">Paraclete Multimedia Website Design</a>';
	echo ' &nbsp;|&nbsp; All Rights Reserved &nbsp;|&nbsp; <a href="http://paracletewebdesign.com/">Paraclete Multimedia Website Design</a>';
	echo '</p></div>';
}
*/

//* Register sidebar
genesis_register_sidebar( array(
	'id'            => 'footer-w',
	'name'          => __( 'Copyright', 'footer-w' ),
	'description'   => __( 'This is a widget area in the footer', 'footer-w' ),
) );

//* Hook copyright in footer

add_action( 'genesis_footer', 'sp_after_post_widget' );
	function sp_after_post_widget() {
		genesis_widget_area( 'footer-w', array(
			'before' => '<div class="after-post widget-area">',
			'after' => '</div>',
	) );
}
//* Remove the Credit footer
remove_action( 'genesis_footer', 'genesis_do_footer' );





//* Go typekit! - add: data-cfasync="false"  for cloudflare - load early forget fout - change CHANGEME

add_action( 'genesis_meta', 'child_typekit' );
function child_typekit() {
echo '<script src="https://use.typekit.net/bmo3mec.js"></script>
<script>try{Typekit.load({ async: true });}catch(e){}</script>
';
}


/*
// Add Image to Sidebar
	function goSideImage() {
		if (!is_front_page()){
			echo '<div class="sidefeature">';
			echo the_post_thumbnail('full');
			echo '</div>';
			};
	}
	add_action('genesis_after_sidebar_widget_area', 'goSideImage');
*/


//* Advanced Custom Field Image - return image object to get height and width - yeah for optimized standards
/*
add_action( 'genesis_before_content', 'sp_plan_post_field' );

	function sp_plan_post_field() {
		
		
	if (get_field('left_side_picture_1')) {	
	$image = get_field('left_side_picture_1');
	// vars
	$url = $image['url'];
	$width = $image['sizes'][ $size . '-width' ];
	$height = $image['sizes'][ $size . '-height' ];

	echo '<div class="architect"><img src="' . $url . '" alt="' . get_field('left_side_picture_1_alt') . '" width="' . $width . '" height="' . $height . '" /></div>';
	
	}
		
		
		if (is_front_page()){	
			genesis_widget_area( 'architect-plan', array(
				'before' => '<div class="architect-plan widget-area">',
				'after' => '</div>',
			) );
		}

	
	
}

*/

	
//* Remove the header right widget area
// unregister_sidebar( 'header-right' );


//* clear for old ie8
/*
	function clearMe() {
			echo '<div style="clear:both;"></div>';
	}
	add_action('genesis_after_entry', 'clearMe');
*/



//* Reposition the primary navigation menu
/*
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_before_content', 'genesis_do_nav' );
*/

// Add Anything Slider - an example how to add html etc



	/** function goPortfolio() {

		if ( is_page() || !is_front_page() ) {

			echo '<div class="FeatureArea">';
			for($i = 1; $i <= 5 && !empty(get_field('portfolio_image_' . $i)); $i++) {
				$s = get_field('portfolio_image_' . $i);
				echo '<div class="portfolio port1"><div class="port-bg" style="background-image: url(', $s ,');"><div class="port-dots"><a href="',get_field('portfolio_link_1'),'"><h2>',get_field('portfolio_title_1'),'</h2></a></div></div></div>';
			}
				
		echo '<div style="clear:both; font-size:0px;">&nbsp;</div></div>';		
		};
	}

	add_action('genesis_before_entry', 'goPortfolio'); */

//add_action('wp_head', 'choose_random_bg');
add_action('genesis_after_header', 'secondary_bg');
add_action('wp_footer', 'rotating_bgs');
//add_filter('genesis_attr_site-header', 'random_bg_class');

function secondary_bg() {
	global $post;
	if(is_page('portfolio') || ($post->post_parent === 121)) {
		return;
	}
	?>
	<div class="header-bg"></div>
	<div class="secondary-bg"></div>
	<?php
}
function rotating_bgs() {
	global $post;
	if(is_page('portfolio') || ($post->post_parent === 121)) {
		return;
	}
	$args = array(
		'post_type' => 'random_bgs',
		'post_count' => -1,
	);
	$query1 = new WP_Query($args);
	$query1->the_post();
	$randMax = sizeOf($query1->posts) - 1;
		 wp_reset_postdata();
	$starter;
	foreach($query1->posts as $key => &$bg) {
		if(!empty(get_field('starting_bg', $bg->ID ))&& get_field('starting_bg', $bg->ID) == $post->ID) {
			$bg->is_starter = 'yes';
			$starter[$key] = 'yes';
		}
		else {
			$bg->is_starter = 'no';
			$starter[$key] = 'no';
		}
		$bg->bg_url =  get_post_meta($bg->ID, 'upload_image', true);
	}
	unset($bg);
	$bg_arr = $query1->posts;
	array_multisort($starter, SORT_DESC, SORT_STRING, $bg_arr);
	echo '<style>
			.header-bg {
				background-image: url(\'' .  $bg_arr[0]->bg_url . '\');
			}
		</style>';
	if(!is_page("portfolio") && !($post->post_parent === 121)) {
		$test = json_encode($bg_arr);
		?>
		<script>
			var test = <?php print_r($test); ?>;
			function rotate_bgs(imgs, i) {
				if(!imgs[i]) {
					i = 0;
				}
				jQuery(function($) {
					$('.secondary-bg').css('background-image', "url(\'" + imgs[i]['bg_url'] + "\')");
					$(".header-bg").fadeOut('slow', function() {
						console.log("test");
						$(".header-bg").css('background-image', "url(\'" + imgs[i]['bg_url'] + "\')");
						$(".header-bg").fadeIn('fast');
						i++;
						setTimeout(function() {rotate_bgs(imgs, i)}, 5000);
					});
				});
				//jQuery('.site-header').css('background-image', "url(\'" + imgs[i]['bg_url'] + "\')");
			}
			setTimeout(function() {rotate_bgs(test, 0)}, 5000);
		</script>
	<?php
	}
}

function random_bg_class($attributes) {
	$attributes['class'] .= ' random-bg';
	return $attributes;
}	

add_action('genesis_header', 'mobile_hamburger_menu');
add_action('wp_footer', 'mobile_hamburger_toggle');
//add_filter( 'genesis_attr_nav-primary', 'primary_nav_visibility' );

function mobile_hamburger_menu() {
	?>
	<div class="mobile-hamburger-menu">
		<span class="hamburger-line closed"></span>
	</div>
	<?php
}
function mobile_hamburger_toggle() {
	?>
	<script>
		jQuery(function($) {
			$('.mobile-hamburger-menu').click(function() {
				if($('.hamburger-line').hasClass('closed')) {
					$('.hamburger-line').addClass('open').removeClass('closed');
					$('.nav-primary').addClass('nav-primary-visible');
					$('.site-container').addClass('site-container-slide');
				}
				else {
					$('.hamburger-line').addClass('closed').removeClass('open');
					$('.nav-primary').removeClass('nav-primary-visible');
					$('.site-container').removeClass('site-container-slide');
				}
			});
		});
	</script>
	<?php
}

remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav' );

add_action('genesis_header', 'svg_logo');
add_action('genesis_after_header', 'header_bottom');
add_action('genesis_before_entry', 'portfolio_header_bottom');

function svg_logo() {
	?>
	<div class="svg-logo-wrap">
		<svg width="100%" height="100%" viewbox="0 0 400 62">
			<image xlink:href="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.svg" width="400" height="62" x="0" y="0" />
		</svg>
	</div>
	<?php
}
function header_bottom() {
	global $post;
	if(is_page('portfolio') || ($post->post_parent === 121)) {
		return;
	}
	?>
	<span class="header-bottom"></span>
	<?php
}
function portfolio_header_bottom() {
	global $post;
	if(!is_page('portfolio') && !($post->post_parent === 121)) {
		return;
	}
	?>
	<span class="header-bottom"></span>
	<?php
}
	
add_action('init', 'register_random_bgs');
function register_random_bgs() {
  $labels = array(
    'name' => _x('Background Images', 'post type general name'),
    'singular_name' => _x('Background Image', 'post type singular name'),
    'add_new' => _x('Add Image', 'before_and_after'),
    'add_new_item' => __('Add New Background Image')

  );
 
 $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'hierarchical' => true,
    'menu_position' => null,
    'supports' => array('title','excerpt')); 
  
  register_post_type('random_bgs',$args);

}

$meta_box = array( 
	'id' => 'bgs_meta',
	'title' => 'Background Image',
	'page' => 'random_bgs',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		array(
			'name' => 'Background',
			'desc' => 'Select a Background Image',
			'id' => 'upload_image',
			'type' => 'text',
			'std' => ''
		),
				array(
			'name' => '',
			'desc' => 'Select an After Image',
			'id' => 'upload_image_button',
			'type' => 'button',
			'std' => 'Browse'
		),
	)
);
 
/**
 * Get post meta in a callback
 *
 * @param WP_Post $post    The current post.
 * @param array   $metabox With metabox id, title, callback, and args elements.
 */
 

add_action('add_meta_boxes_random_bgs', 'bgs_meta');

// Add meta box
function bgs_meta() {
	global $meta_box;
	
	add_meta_box($meta_box['id'], $meta_box['title'], 'mytheme_show_box', $meta_box['page'], $meta_box['context'], $meta_box['priority'], $meta_box['fields']);

}

// Callback function to show fields in meta box
function mytheme_show_box() {
	global $meta_box, $post;
	
	// Use nonce for verification
	echo '<input type="hidden" name="mytheme_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($meta_box['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<td>';
		switch ($field['type']) {
			



//If Text		
			case 'text':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />',
					'<br />', $field['desc'];
				break;
			

//If Text Area			
			case 'textarea':
				echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>',
					'<br />', $field['desc'];
				break;


//If Button	
				
				case 'button':
				echo '<input type="button" name="', $field['id'], '" id="', $field['id'], '"value="', $meta ? $meta : $field['std'], '" />';
				break;
		}
		echo 	'<td>',
			'</tr>';
	}
	
	echo '</table>';
}

add_action('save_post', 'mytheme_save_data');

// Save data from meta box
function mytheme_save_data($post_id) {
	global $meta_box;
	
	// verify nonce
	if (!wp_verify_nonce($_POST['mytheme_meta_box_nonce'], basename(__FILE__))) {
		return $post_id;
	}

	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
	
	foreach ($meta_box['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}

function my_admin_scripts() {
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
wp_register_script('my-upload', get_bloginfo('stylesheet_directory') . '/functions/my-script.js', array('jquery','media-upload','thickbox'));
wp_enqueue_script('my-upload');
}
function my_admin_styles() {
wp_enqueue_style('thickbox');
}
add_action('admin_print_scripts', 'my_admin_scripts');
//add_action('admin_print_styles', 'my_admin_styles');

add_action('admin_footer', 'page_id_field', 20);

function page_id_field() {
	global $post;
	//print_r($post);
	echo '<input id="post-id" type="hidden" value="' . $post->ID . '" />'; 
}

add_action('genesis_after_header', 'get_portfolio');
add_action('genesis_before_entry', 'portfolio_links');

function get_portfolio() {
	global $post;
	if(!is_page('portfolio') && !($post->post_parent === 121)) {
		return;
	}
	echo do_shortcode(get_field('portfolio_slider_shortcode'));
}
function portfolio_links() {
	global $post;
	if(!is_page('portfolio') && !($post->post_parent === 121)) {
		return;
	}
	$args1 = array(
				'post_type' => 'page',
				'page_id' => 121
	);
	$portfolio_title = get_field('portfolio_title', 121);
	if(empty($portfolio_title)) {
		$query1 = new WP_Query($args1);
		$portfolio_title = $query1->posts[0]->post_title;
	}
	echo '<div class="portfolio-nav">
			<a href="/portfolio"' . ($post->ID === 121 ? ' class="cur-portfolio"' : "") . '>
				<span>' . 
					$portfolio_title . 
				'</span>
			</a>';
	$args2 = array(
				'post_type' => 'page',
				'post_parent' => 121
	);	
	$query2 = new WP_Query($args2);
	foreach($query2->posts as $port_post) {
		$portfolio_title = get_field('portfolio_title', $port_post->ID);
		if(empty($portfolio_title)) {
			$portfolio_title = $port_post->post_title;
		}
		echo '<a href="' . get_permalink($port_post->ID) . '"' . ($post->ID === $port_post->ID ? ' class="cur-portfolio"' : "") . '>
				<span>' . 
					$portfolio_title . 
				'</span>
			</a>';
	}
	echo '</div>';
	wp_reset_postdata();
}

add_action('genesis_entry_content', 'about_bios');
add_shortcode('services_boxes', 'get_service_boxes');

function about_bios() {
	if(!is_page('about')) {
		return;
	}
	$fields = get_fields();
	echo '<div class="about-bios-wrap"><!--';
	foreach($fields as $field => $value) {
		if(preg_match("/bio_[0-9]+$/", $field) && !empty($value)) {
			echo '--><div class="about-bio">' . 
					$value .
				'</div><!--';
		}
	}
	echo '--></div>';
}
function get_service_boxes() {
	if(!is_page('services')) {
		return;
	}
	$output;
	$fields = get_fields();
	$output .= '<div class="service-blocks-wrap"><!--';
	foreach($fields as $field => $value) {
		if(preg_match("/service_block_[0-9]+$/", $field) && !empty($value)) {
			$img = get_field($field . '_image');
			$output .= '--><div class="service-block-outer" style="background-image: url(\'' . $img['url'] . '\')">
						<span>' .
							$value . 
						'</span>' .
						(!empty(get_field($field . '_title')) ? '<div class="service-header">
							<h1>' . 
								get_field($field . '_title') .
							'</h1>
						</div>' : "") .
						'<div class="service-block-inner">
						</div>
					</div><!--';
		}
	}
	$output .= '--></div>';
	return $output;
}

add_action('wp_footer', 'fixed_header_script');

function fixed_header_script() {
	?>
	<script>
		jQuery(function($) {
			$(document).scroll(function() {
				if($("html").scrollTop() === 0 && $(window).scrollTop() === 0) {
					if(!$(".site-header .wrap").hasClass("fixed-scroll")) {
						return;
					}
					$(".site-header .wrap").removeClass("fixed-scroll");
				}
				else {
					if($(".site-header .wrap").hasClass("fixed-scroll")) {
						return;
					}
					$(".site-header .wrap").addClass("fixed-scroll");
				}
			});
		});
	</script>
	<?php
}
