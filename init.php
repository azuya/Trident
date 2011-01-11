<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forums Bootstrap
 */

$routing_config = array(
    'api'   => Kohana::config('forum/routes/api'),
    'front' => Kohana::config('forum/routes/front')
);

# Set up the API routes
foreach($routing_config['api']->routes as $name => $route)
{
    
    $name = $routing_config['api']['name_base'].$name;
    $uri  = $routing_config['api']['uri_base'].$route['uri'];
    
    $patterns = array_key_exists('patterns', $route) ? $route['patterns'] : array();
    $defaults = array_key_exists('defaults', $route) ? $route['defaults'] : array();
    
    $defaults['directory'] = $routing_config['api']['default_directory'];
    $defaults['format']    = $routing_config['api']['default_format'];
    
    Route::set($name, $uri, $patterns)->defaults($defaults);
}

# Set up the Front routes
foreach($routing_config['front']->routes as $name => $route)
{
    $name = $routing_config['front']['name_base'].$name;
    $uri  = $routing_config['front']['uri_base'].$route['uri'];
    
    $patterns = array_key_exists('patterns', $route) ? $route['patterns'] : array();
    $defaults = array_key_exists('defaults', $route) ? $route['defaults'] : array();
    
    $defaults['directory'] = $routing_config['front']['default_directory'];
    Route::set($name, $uri, $patterns)->defaults($defaults);
}

/*# Front Routing

Route::set('forums.index', 'forums')
    ->defaults(array(
        'directory'  => 'forum/front',
        'controller' => 'forum',
        'action'     => 'index'
    ));
*/




# API Routing

/*Route::set('forums.api.index', 'forums/api/forum(.<format>)',
    array(
        'format'     => '(html|json)',
    ))
    ->defaults(array(
        'directory'  => 'forum/api',
        'controller' => 'forum',
        'action'     => 'index',
        'format'     => 'html',
    ));

Route::set('forums.api.forum.new', 'forums/api/category/<category_id>/new-forum(.<format>)',
	array(
		'format'     => '(html|json)',
		'category_id'   => '(\d+)',
	))
	->defaults(array(
		'directory'  => 'forum/api',
		'controller' => 'forum',
		'action'     => 'new',
		'format'     => 'html',
	));

Route::set('forums.api.post.new', 'forums/api/thread/<thread_id>/reply(.<format>)',
	array(
		'format'     => '(html|json)',
		'thread_id'   => '(\d+)',
	))
	->defaults(array(
		'directory'  => 'forum/api',
		'controller' => 'post',
		'action'     => 'new',
		'format'     => 'html',
	));

Route::set('forums.api.thread.new', 'forums/api/<forum_id>/new-thread(.<format>)',
	array(
		'format'     => '(html|json)',
		'forum_id'   => '(\d+)',
	))
	->defaults(array(
		'directory'  => 'forum/api',
		'controller' => 'thread',
		'action'     => 'new',
		'format'     => 'html',
	));
	
Route::set('forums.api.default', 'forums/api/<controller>(/<id>)/<action>(.<format>)',
    array(
        'format' => '(html|json)',
        'id' => '(\d+)',
    ))
    ->defaults(array(
        'directory' => 'forum/api',
        'format' => 'html'
    ));*/