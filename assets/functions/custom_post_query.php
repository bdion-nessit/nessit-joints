<?php
function do_custom_post_query() {
    $data = filter_input_array(INPUT_POST, array(
        'data' => array(
            'filter' => FILTER_SANITIZE_STRING,
            'flags' => FILTER_REQUIRE_ARRAY,
        ),
    ));
    $data = $data['data'];
	$data['query_data']['post_type'] = (!empty($data['query_data']['post_type']) ? $data['query_data']['post_type'] : 'post');
	
    $args = array();
    foreach($data['query_data'] as $i => $item) {
        $args[$i] = $item;
    }
	if(!empty($data['taxonomy']) && !empty($data['terms'])) {
		$args['tax_query'] = array(
			'relation' => 'OR',
			array(
				'taxonomy' => $data['taxonomy'],
				'terms' => (preg_match("/\,/", $data['terms']) ? explode(',', $data['terms']) : $data['terms']),
			),
		);
	}
    $query1 = new WP_Query($args);
    $output = array();
	$output['args'] = $args;
    if(!empty($data['callback'])) {
        $output['content'] = $data['callback']($query1);
        $output['type'] = 'string';
    }
	else {
		$output['type'] = 'string';
		
		if($query1->have_posts()) {
			ob_start();
			$j = 0;
			while($query1->have_posts()) {
				$query1->the_post();
				require(get_stylesheet_directory() . '/parts/loop-archive.php');
				$j++;
			}
			$output['j'] = $j;
			$output['content'] = ob_get_contents();
			ob_end_clean();
		}
		else {
			$output['content'] = '<article id="" class="post-filler post type-' . $data['query_data']['post_type'] . ' status-publish format-standard hentry" role="article">	';
		}
		wp_reset_postdata();
	}
	
	if(!empty($data['remove_target'])) {
		$output['remove_target'] = $data['remove_target'];
	}
    
    print(json_encode($output));
    die();
}