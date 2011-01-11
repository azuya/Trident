<?php defined('SYSPATH') or die('No direct script access.');

return array(
    'uri_base'          => 'forums/api/',
    'name_base'         => 'forums.api.',
    'default_format'    => 'html',
    'default_directory' => 'forum/api',
    
    /**
     * All the api routes in a config array
     * Format is:
     *     '<name>' => array(
     *         'uri' => '<uri pattern>',
     *         ['patterns' => array(
     *             '<uri_variable>' => '<pattern>'
     *             ...
     *         ),]
     *         'defaults' => array(
     *             '<uri_variable>' => '<default_value>',
     *             ....
     *         ),
     *     ),
     * 
     * We've put it in a config array and built the routes from config in 
     * the module bootstrap so that routes can be overridden in application
     */
    
    'routes' => array(
        
        # Forum Index
        'index' => array(
            'uri'      => 'forum(.<format>)',
            'defaults' => array(
                'controller' => 'forum',
                'action'     => 'index',
            ),
        ),
        
        /**
         * The new thread, new forum and new post routes
         * each have a special because of the way the url
         * needs to include their parent model id and name
         */
        
        # New Forum
        'forum.new' => array(
            'uri'      => 'category/<category_id>/new-forum(.<format>)',
            'patterns' => array(
                'category_id' => '(\d+)',
            ),
            'defaults' => array(
                'controller' => 'forum',
                'action'     => 'new',
            ),
        ),
        
        # New Post
        'post.new' => array(
            'uri'      => 'thread/<thread_id>/reply(.<format>)',
            'patterns' => array(
                'thread_id' => '(\d+)',
            ),
            'defaults' => array(
                'controller' => 'post',
                'action'     => 'new',
            ),
        ),
        
        # New Thread
        'thread.new' => array(
            'uri'      => 'forum/<forum_id>/new-thread(.<format>)',
            'patterns' => array(
                'forum_id' => '(\d+)',
            ),
            'defaults' => array(
                'controller' => 'thread',
                'action'     => 'new',
            ),
        ),
        
        /**
         * Setting user groups
         */
        'user.groups' => array(
            'uri'      => 'user/<user_id>/groups(.<format>)',
            'patterns' => array(
                'user_id' => ('\d+'),
            ),
            'defaults' => array(
                'controller' => 'group',
                'action'     => 'user'
            ),
        ),
        
        /**
         * The default route
         */
        
        #Default Route
        'default' => array(
            'uri'      => '<controller>(/<id>)/<action>(.<format>)',
            'patterns' => array(
                'id' => '(\d+)',
            )
        ),
    ),
);