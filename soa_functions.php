<?php
define( 'HEADER_IMAGE_WIDTH', apply_filters( 'twentyten_header_image_width', 457 ) );
define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'twentyten_header_image_height', 152 ) );
//Remove the custom options provided by the default twentyeleven theme.
add_action( 'after_setup_theme','remove_twentytwelve_options', 100 );
function remove_twentytwelve_options() {

	remove_custom_background();
	remove_action('admin_menu', 'twentytwelve_theme_options_add_page');

}
function is_tree( $pid ) {      // $pid = The ID of the page we're looking for pages underneath
    global $post;               // load details about this page

    if ( is_page($pid) )
        return true;            // we're at the page or at a sub page

    $anc = get_post_ancestors( $post->ID );
    foreach ( $anc as $ancestor ) {
        if( is_page() && $ancestor == $pid ) {
            return true;
        }
    }

    return false;  // we arn't at the page, and the page is not an ancestor
}
if ( function_exists('register_sidebar') ) {
	register_sidebar( array(
		'name' => __( 'Social Media Footer', 'twentytwelve' ),
		'id' => 'socialmediafooter',
		'description' => __( 'A Widget Area for the Social Media Footer', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
if ( function_exists('register_sidebar') ) {
	register_sidebar( array(
		'name' => __( 'Drill Design Sidebar 1', 'twentytwelve' ),
		'id' => 'specialeventsidebar1',
		'description' => __( 'A Widget Area for the Drill Design Pages', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
if ( function_exists('register_sidebar') ) {
	register_sidebar( array(
		'name' => __( 'Drill Design Sidebar 2', 'twentytwelve' ),
		'id' => 'specialeventsidebar2',
		'description' => __( 'A Widget Area for the Drill Design Pages', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
if ( function_exists('register_sidebar') ) {
	register_sidebar( array(
		'name' => __( 'Day of Percussion Sidebar 1', 'twentytwelve' ),
		'id' => 'dopeventsidebar1',
		'description' => __( 'A Widget Area for the Day of Percussion Pages', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
if ( function_exists('register_sidebar') ) {
	register_sidebar( array(
		'name' => __( 'Day of Percussion Sidebar 2', 'twentytwelve' ),
		'id' => 'dopeventsidebar2',
		'description' => __( 'A Widget Area for the Day of Percussion Pages', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_theme_support( 'post-thumbnails' );
add_image_size( 'footer-image', '452', '210', true );

add_filter( 'image_size_names_choose', 'custom_image_sizes_choose' );  
function custom_image_sizes_choose( $sizes ) {  
    $custom_sizes = array(  
        'footer-image' => 'Footer Image'  
    );  
    return array_merge( $sizes, $custom_sizes );  
} 

function add_last_modified_column($columns) {
    return array_merge( $columns,
              array("last_modified" => __("Last Modified")) );
}
add_filter("manage_pages_columns" , "add_last_modified_column");
add_filter("manage_posts_columns" , "add_last_modified_column");

function last_modified_column( $column, $post_id ){
  if( "last_modified" == $column ) {
    the_modified_time( "F j, Y" );
  }
}
add_action( "manage_pages_custom_column" , "last_modified_column", 10, 2 );
add_action( "manage_staff_bios_posts_custom_column" , "last_modified_column", 10, 2 );
add_action( "manage_member_bios_posts_custom_column" , "last_modified_column", 10, 2 );
add_action( "manage_post_posts_custom_column" , "last_modified_column", 10, 2 );

function last_modified_column_register_sortable( $columns ) {
	$columns["last_modified"] = "last_modified";

	return $columns;
}
add_filter( "manage_edit-page_sortable_columns", "last_modified_column_register_sortable" );
add_filter( "manage_edit-staff_bios_sortable_columns", "last_modified_column_register_sortable" );
add_filter( "manage_edit-member_bios_sortable_columns", "last_modified_column_register_sortable" );
add_filter( "manage_edit-post_sortable_columns", "last_modified_column_register_sortable" );

function sort_column_by_modified( $vars ){
	if ( isset( $vars["orderby"] ) && "last_modified" == $vars["orderby"] ) {
		$vars = array_merge( $vars, array(
			"orderby" => "modified"
		) );
	}
	return $vars;
}
add_filter( "request", "sort_column_by_modified" );

define('WP_MEMORY_LIMIT', '32M');

add_action( 'init', 'create_staff_bios_type' );
function create_staff_bios_type() {
  register_post_type( 'staff_bios',
    array(
      'labels' => array(
        'name' => __( 'Staff Bios' ),
        'singular_name' => __( 'Staff Bio' )
      ),
      'public' => true,
      'has_archive' => true,
    )
  );
}
add_action( 'init', 'sk_add_category_taxonomy_to_staff_bios' );
function sk_add_category_taxonomy_to_staff_bios() {
	register_taxonomy_for_object_type( 'category', 'staff_bios' );
}

add_action( 'init', 'create_member_bios_type' );
function create_member_bios_type() {
  register_post_type( 'member_bios',
    array(
      'labels' => array(
        'name' => __( 'Member Bios' ),
        'singular_name' => __( 'Member Bio' )
      ),
      'public' => true,
      'has_archive' => true,
    )
  );
}
//add_action( 'init', 'sk_add_category_taxonomy_to_member_bios' );
function sk_add_category_taxonomy_to_member_bios() {
	register_taxonomy_for_object_type( 'category', 'member_bios' );
}
register_taxonomy( 'member_category', 'member_bios', array(
														'labels' => array(
															'name' => _x( 'Categories', 'taxonomy general name' ), 
															'singular_name' => _x( 'Category', 'taxonomy singular name' ),
															'all_items' => __('All Categories'),
															'edit_item' => __('Edit Category'),
															'view_item' => __('View Category'),
															'update_item' => __('Update Category'),
															'add_new_item' => __('Add Category'),
															'parent_item' => __('Parent Category'),
															'parent_item_colon' => __('Parent Category:')),
														'hierarchical' => true,
														'sort' => false) );
															

add_action('pre_get_posts', 'member_bio_archive_count');

function member_bio_archive_count(&$query) {
if (is_tax('member_category')) {
    $query->set('posts_per_page', -1);
}
return;
}															
															add_shortcode('staff_bios', 'get_staff_bios');
add_shortcode('member_bios', 'get_member_bios');
add_action('wp_footer', 'show_bio_script', 9);

function get_staff_bios() {
	$output;
	$categories_list = get_the_category_list( __( ', ', 'SOA Gym Class' ) );
	if(!empty($_GET['alpha']) && $_GET['alpha'] === 'true') {
		$args = array('post_type' => 'staff_bios',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => '-1');
		$query1 = new WP_Query($args);
		while($query1->have_posts()) {
			$query1->the_post();
			$content = get_the_content();
			$content = apply_filters('the_content', $content);
			$content = str_replace(']]>', ']]&gt;', $content);
			$output .= '<div class="staff-bio ' . $slug . '">
			<h2>' . get_the_title() . '</h2>' . 
			$content . 
			'</div>';
		}
	}
	else {
		//Dubai Project Staff Category
		$args = array('post_type' => 'staff_bios',
			'category_name' => 'dubai-project-staff',
			'order' => 'ASC',
			'posts_per_page' => '-1');
		$output .= get_bio_category($args, 'dubai-staff-project', '', 'Designers & Arrangers');
		
		//Instructionall Staff Category
		//Brass Staff Subcategory
		$args = array('post_type' => 'staff_bios',
			'category_name' => 'brass',
			'order' => 'ASC',
			'posts_per_page' => '-1');
		$output .= '<h1 class="instructional">INSTRUCTIONAL STAFF</h1>' . get_bio_category($args, 'brass', 'Brass Staff', 'Brass Staff');
		
		//Woodwind Staff Subcategory
		$args = array('post_type' => 'staff_bios',
			'category_name' => 'woodwind',
			'order' => 'ASC',
			'posts_per_page' => '-1');
		$output .= get_bio_category($args, 'woodwind', 'Woodwind Staff', 'Woodwind Staff');
		
		//Percussion Staff Subcategory
		$args = array('post_type' => 'staff_bios',
			'category_name' => 'percussion',
			'order' => 'ASC',
			'posts_per_page' => '-1');
		$output .= get_bio_category($args, 'percussion', "Percussion Staff", "Percussion Staff");
		
		//Color Guard Subcategory
		$args = array('post_type' => 'staff_bios',
			'category_name' => 'color-guard',
			'order' => 'ASC',
			'posts_per_page' => '-1');
		$output .= get_bio_category($args, 'color-guard', "Color Guard Staff", "Color Guard Staff");
		
		//Visual Staff Category
		$args = array('post_type' => 'staff_bios',
			'category_name' => 'visual-staff',
			'order' => 'ASC',
			'posts_per_page' => '-1');
		$output .= get_bio_category($args, 'visual-staff', "Visual Staff", "Visual Staff");
		
		$output .= '<div class="alphabetical"><a href="/staff/?alpha=true">View Staff Alphabetically</a></div>';
		}
	return $output;
}
function get_bio_category($args, $slug, $header_text="", $button_text="") {
	//initializes the temporary variable for the start of the output
	//adds a header if the text is included
	$temp_start = (!empty($button_text) ? "<h1>$header_text</h1>" : "");
	//initializes the temporary variable for the end of the output
	$temp_end;
	//initializes variable to track if the category contains hidden entries
	$has_hidden = false;
	$query1 = new WP_Query($args);
	while($query1->have_posts()) {
		$query1->the_post();
		//if the post isn't hidden
		if(get_field('visibility', $query1->post->ID) != 'hidden') {
			//if the post isn't a duplicate
			if(get_field('duplicate', $query1->post->ID) != 'yes') {
				//gets the content and applies Wordpress' formatting
				$content = get_the_content();
				$content = apply_filters('the_content', $content);
				$content = str_replace(']]>', ']]&gt;', $content);
				//adds the post to the beginning of the output via the temporary variable
				$temp_start .= '<div id="staff-bio-' . $query1->post->ID . '" class="staff-bio ' . $slug . '">
				<h2>' . get_the_title() . '</h2>' . 
				$content . 
				'</div>
				<div style="clear: both;"></div>';
			}
			//else if the post is a duplicate and links to an original post
			elseif(!empty(get_field('original_link', $query1->post->ID))) {
				$original = get_field('original_link', $query1->post->ID);
				//adds a jump link to the original post to the beginning of output via the temporary variable
				$temp_start .= '<div id="staff-bio-' . $query1->post->ID . '" class="staff-bio ' . $slug . ' staff-bio-hidden">
					<a href="/staff/#staff-bio-' . $original->ID . '"><h2>' . get_the_title() . '</h2></a>
				</div>';
			}
		}
		else {
			//if the post isn't a duplicate
			if(get_field('duplicate', $query1->post->ID) != 'yes') {
				//indicates the category has hidden posts
				$has_hidden = true;
				//gets the content and applies Wordpress' formatting
				$content = get_the_content();
				$content = apply_filters('the_content', $content);
				$content = str_replace(']]>', ']]&gt;', $content);
				//adds the post to the end of the output, after the visible posts, via the temporary variable
				$temp_end .= '<div id="staff-bio-' . $query1->post->ID . '" class="staff-bio ' . $slug . ' staff-bio-hidden">
				<h2>' . get_the_title() . '</h2>' . 
				$content . 
				'</div>
				<div style="clear: both;"></div>';
			}
			//else if the post is a duplicate and links to an original post
			elseif(!empty(get_field('original_link', $query1->post->ID))) {
				$original = get_field('original_link', $query1->post->ID);
				//adds a jump link to the original post to the end of output, after the visible posts, via the temporary variable
				$temp_end .= '<div id="staff-bio-' . $query1->post->ID . '" class="staff-bio ' . $slug . ' staff-bio-hidden">
					<a href="/staff/#staff-bio-' . $original->ID . '"><h2>' . get_the_title() . '</h2></a>
				</div>';
			}
		}
	}
	wp_reset_postdata();
	//if there are hidden posts, add buttons to show and hide them to the very end of the category
	$temp_end .= ((!empty($button_text) && $has_hidden) ? '<div class="bio-show" data-category="' . $slug . '">View All ' . $button_text . '</div>
			<div class="bio-hide" data-category="' . $slug . '">Hide ' . $button_text . '</div>' : "");
	return $temp_start . $temp_end;
}
function get_member_bios() {
	//creates a list of all member categories that aren't empty
	$terms = get_terms( 'member_category', array(
	'orderby' => 'term_id',
    'hide_empty' => true,
) );
//print_r($terms);
//organizes categories into an array where children are organized under their parents
$categoryArr = array();
	foreach($terms as $term) {
		$temp = set_parents($term);
		
			//$categoryArr[$term->parent][] = $term->term_id;
		if(!isset($categoryArr[$temp[0]])) {
			$categoryArr[$temp[0]] = $temp[1];
		} 
		else {
			$categoryArr[$temp[0]] = array_replace_recursive($categoryArr[$temp[0]], $temp[1]);
		}
	}
	//$categoryArr = array_reverse($categoryArr, true);
	//print_r($categoryArr);
	$output = get_member_cat_titles($categoryArr);
	return $output;
}
function set_parents($t, $tempArr = array()) {
	if(!empty($t->parent)) {
		if(empty($tempArr)) {
			$tempArr[$t->term_id] = array();
		}
		else {
			$tempArr = array($t->term_id => $tempArr);
		}
		return set_parents(get_term($t->parent), $tempArr);
	}
		//$categoryArr[$t->term_id] = array_merge_recursive($categoryArr[$t->term_id], $tempArr);
	return array($t->term_id, $tempArr);
}
function get_member_cat_titles($catArr) {
	//load post variable
	global $post;
	//initialize output variable
	$out;
	//print_r($catArr);
	//for each item in $catArr as index => entry
	foreach($catArr as $category => $children) {
		//if the category has children
		if(!empty($children) && (gettype($children) === 'array')) {
			//initialize temporary variable
			$temp = "";
			//query args
			//print_r($children);
			//echo '<br />';
			$children_ids = array();
			foreach($children as $child => $childs_child) {
				$children_ids[] = $child;
			}
			$args = array(
			'post_type' => 'member_bios',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'member_category',
					'field'    => 'term_id',
					'terms'    => $category,
					'include_children' => false
				),
				array(
					'taxonomy' => 'member_category',
					'field'    => 'term_id',
					'terms'    => $children_ids,
					'operator' => 'NOT IN',
				)
			));
			//get term object for the category
			$cat = get_term($category);
			$query1 = new WP_Query($args);
			global $post;
			while($query1->have_posts()) {
				$query1->the_post();
				//store members of the category in the temporary variable
				$temp .= '<a href="/member_category/' . $cat->slug . '/#' . $post->post_name . '">' . get_field('member_roster_name', $post->ID) . '<br />';
			}
			wp_reset_postdata();
			//add the category title and its members' names to the output
			$out .= '<p>
						<a href="/member_category/' . $cat->slug . '"><span style="text-decoration: underline; font-weight: bold;">' . strtoupper($cat->name) . ':</span></a><br />' .
						$temp . 
					'</p>' .
					//recursive function call on the category's children
					get_member_cat_titles($children);
		}
		//when the category has no children
		//base case for the recursive function
		else {
			//initialize temporary variable
			$temp = "";
			//query args
			$args = array(
			'post_type' => 'member_bios',
			'order' => 'ASC',
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'member_category',
					'field'    => 'term_id',
					'terms'    => (gettype($children) === 'array' ? $category : $children),
					'include_children' => false,
				)
			));
			//get term object for the category
			//checks for the possibility that is set to an empty array
			$cat = get_term((gettype($children) === 'array' ? $category : $children));
			$query1 = new WP_Query($args);
			global $post;
			while($query1->have_posts()) {
				$query1->the_post();
				//store members of the category in the temporary variable
				$temp .= '<a href="/member_category/' . $cat->slug . '/#' . $post->post_name . '">' . get_field('member_roster_name', $post->ID) . '<br />';
			}
			wp_reset_postdata();
			//echo $children . " ";
			//add the category title and its members' names to the output
			$out .= '<p>
						<a href="/member_category/' . $cat->slug . '"><span style="text-decoration: underline; font-weight: bold;">' . $cat->name . ':</span></a><br />' .
						$temp . 
					'</p>';
		}
	}
	return $out;
}
function show_bio_script() {
	if(!is_page('staff')) {
		return;
	}
	?>
	<script>
		jQuery(function($) {
			//console.log("test2");
			$(".bio-show").click(function() {
				var temp = $(this);
				$('.staff-bio-hidden.' + temp.data('category')).show();
				$(this).hide()
					.next().show();
			});
			$(".bio-hide").click(function() {
				var temp = $(this);
				$('.staff-bio-hidden.' + temp.data('category')).hide();
				$(this).hide()
					.prev().show();
			});
		});
	</script>
	<?php
}
