<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'title' => array(
		'not_empty'  => 'The forum needs a title.',
		'max_length' => 'Your title can\'t be longer than 256 letters, numbers, spaces or punctuation characters.',
		'default'    => 'There was a problem with the Title field.',
	),
	
	'description' => array(
		'not_empty'  => 'The forum needs a description.',
		'default'    => 'There was a problem with the Description field.'
	),
	
	'category_id' => array(
		'not_empty' => 'You have to pick a category.',
		'default'   => 'There was a problem with the Category field.',
	),
	
	
);