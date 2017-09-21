<?php 
add_action('joints_entry_header', 'get_entry_header', 9);

get_header(); 
do_action('joints_before_content');
?>
			
	<div id="content">

		<div id="inner-content" class="row">
            
            <?php 
                
                do_action('joints_secondary_sidebar');
            ?>
	
			<main id="main" class="large-<?php echo $column_width; ?> medium-<?php echo $column_width; ?> columns first" role="main">

				<?php 
				do_action('joints_entry_header');
				if (have_posts()) {
					while (have_posts()) {
						the_post(); ?>
			 
					<!-- To see additional archive styles, visit the /parts directory -->
					<?php 
						do_action('joints_entry');
					}
					joints_page_navi();
				}
				else { 
					get_template_part( 'parts/content', 'missing' ); 
					blog_filler();
				}
				?>
	
		    </main> <!-- end #main -->
		
		    <?php 
                do_action('joints_primary_sidebar');
            ?>
		
		</div> <!-- end #inner-content -->

	</div> <!-- end #content -->

<?php 
do_action('joints_before_content');
get_footer();
?>
