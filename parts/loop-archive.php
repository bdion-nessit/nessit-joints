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
					do_action('joints_entry_header'); //Default actions defined in core.php

					do_action('joints_entry_content'); //Default actions defined in core.php
					
					do_action('joints_entry_footer'); //Default actions defined in core.php
					?>
				</div>
			</div>
		</div>
	</section>		    						
</article> <!-- end article -->