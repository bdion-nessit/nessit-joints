<?php 
add_action('joints_primary_sidebar', 'get_sidebar');
get_header(); 
do_action('joints_before_content');
?>
			
	<div id="content">

		<div id="inner-content" class="row">
            
            <?php 
                
                do_action('joints_secondary_sidebar');
            ?>
	
			<main id="main" class="large-8 medium-8 columns first" role="main">
				<header>
					<h1 class="archive-title"><?php _e( 'Search Results for:', 'jointswp' ); ?> <?php echo esc_attr(get_search_query()); ?></h1>
				</header>

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			 
					<!-- To see additional archive styles, visit the /parts directory -->
					<?php get_template_part( 'parts/loop', 'archive' ); ?>
				    
				<?php endwhile; ?>	

					<?php joints_page_navi(); ?>
					
				<?php else : ?>
				
					<?php 
					get_template_part( 'parts/content', 'missing' ); 
					blog_filler();
					?>
						
			    <?php endif; ?>
	
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
