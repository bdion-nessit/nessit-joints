<?php
function do_custom_post_query() {
    $data = filter_input_array(INPUT_POST, array(
        'data' => array(
            'filter' => FILTER_SANITIZE_STRING,
            'flags' => FILTER_REQUIRE_ARRAY,
        ),
    ));
    $data = $data['data'];
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
    if(!empty($data['callback'])) {
        $output['content'] = $data['callback']($query1);
        $output['type'] = 'string';
    }
    
    print(json_encode($output));
    die();
}