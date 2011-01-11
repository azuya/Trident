<?php defined('SYSPATH') or die('No direct script access.');

return array(
    'uri_base'          => 'forums',
    'name_base'         => 'forums.front.',
    'default_directory' => 'forum/front',
    
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
            'uri'      => '',
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
            'uri'      => '/category/<category_id>/new-forum',
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
            'uri'      => '/thread/<thread_id>/reply',
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
            'uri'      => '/forum/<forum_id>/new-thread',
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
            'uri'      => '/user/<user_id>/groups',
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
            'uri'      => '/<controller>(/<id>)/<action>',
            'patterns' => array(
                'id' => '(\d+)',
            )
        ),
    ),
);
