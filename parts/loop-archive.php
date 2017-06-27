<?php
add_action('joints_entry_header', 'get_archive_title');

add_action('joints_entry_header', 'get_byline');
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(''); ?> role="article">	
	<section class="post-content">
		<div class="vc_row vc_row-o-equal-height vc_row-flex">
			<div class="vc_column_container vc_col-sm-12">	
				<div class="vc_column-inner">	

					<?php 
					do_action('joints_entry_header');

					do_action('joints_entry_content'); 
					?>
										
					<footer class="article-footer">
				    	<p class="tags"><?php the_tags('<span class="tags-title">' . __('Tags:', 'jointstheme') . '</span> ', ', ', ''); ?></p>
					</footer> <!-- end article footer -->	
				</div>
			</div>
		</div>
	</section>		    						
</article> <!-- end article -->