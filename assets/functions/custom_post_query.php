<?php
function do_custom_post_query() {
	//Get and santize data
    $data = filter_input_array(INPUT_POST, array(
        'data' => array(
            'filter' => FILTER_SANITIZE_STRING,
            'flags' => FILTER_REQUIRE_ARRAY,
        ),
    ));
   
	$data = $data['data']; //Simplify data variable
    $args = array(); //Initialize array of args for query
	
	//Turns the query data into args for query
    foreach($data['query_data'] as $i => $item) {
        $args[$i] = $item;
    }
	
	//If a taxonomy was included in the data, add a tax query to the args for query
	if(!empty($data['taxonomy']) && !empty($data['terms'])) {
		$args['tax_query'] = array(
			'relation' => 'OR',
			array(
				'taxonomy' => $data['taxonomy'],
				'terms' => (preg_match("/\,/", $data['terms']) ? explode(',', $data['terms']) : $data['terms']),
			),
		);
	}
	
    $query1 = new WP_Query($args); //Get posts for query
    $output = array(); //Initialize array of items to output
	
	//If a callback function was provided
    if(!empty($data['callback'])) {
        $output['content'] = $data['callback']($query1); //Send the query data to the call back function, then add its response to the output
        $output['type'] = 'string'; //Indicates that the returned content is in the form of a string
    }
    
    print(json_encode($output));
    die();
}