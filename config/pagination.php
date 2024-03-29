<?php defined('SYSPATH') or die('No direct script access.');

return array(

	// Application defaults
	'forums' => array(
		'current_page'      => array('source' => 'query_string', 'key' => 'page'),
		'total_items'       => 0,
		'items_per_page'    => 3,
		'view'              => 'pagination/basic',
		'auto_hide'         => TRUE,
		'first_page_in_url' => FALSE,
	),

);
