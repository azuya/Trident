<?php

return array(

	/*
	 * The Authentication library to use
	 * Make sure that the library supports:
	 * 1) A get_user method that returns FALSE when no user is logged in
	 *	  and a user object that implements Acl_Role_Interface when a user is logged in
	 * 2) A static instance method to instantiate a Authentication object
	 *
	 * array(CLASS_NAME,array $arguments)
	 */
	'lib' => array(
		'class'  => 'A1', // (or AUTH)
		'params' => array('a1')
	),

	/**
	 * Throws an a2_exception when authentication fails
	 */
	'exception' => FALSE,

	/*
	 * The ACL Roles (String IDs are fine, use of ACL_Role_Interface objects also possible)
	 * Use: ROLE => PARENT(S) (make sure parent is defined as role itself before you use it as a parent)
	 */
	'roles' => array
	(
		// ADD YOUR OWN ROLES HERE
		'user'	=>	'guest',
		'moderator' => 'user',
		'admin' => 'moderator'
	),

	/*
	 * The name of the guest role 
	 * Used when no user is logged in.
	 */
	'guest_role' => 'guest',

	/*
	 * The ACL Resources (String IDs are fine, use of ACL_Resource_Interface objects also possible)
	 * Use: ROLE => PARENT (make sure parent is defined as resource itself before you use it as a parent)
	 */
	'resources' => array
	(
		// ADD YOUR OWN RESOURCES HERE
		'forum'          => NULL,
    	'forum_category' => NULL,
    	'forum_thread'   => NULL,
    	'forum_post'     => NULL,
    	'forum_group'    => NULL,
	),

	/*
	 * The ACL Rules (Again, string IDs are fine, use of ACL_Role/Resource_Interface objects also possible)
	 * Split in allow rules and deny rules, one sub-array per rule:
	     array( ROLES, RESOURCES, PRIVILEGES, ASSERTION)
	 *
	 * Assertions are defined as follows :
			array(CLASS_NAME,$argument) // (only assertion objects that support (at most) 1 argument are supported
			                            //  if you need to give your assertion object several arguments, use an array)
	 */
	'rules' => array
	(
		'allow' => array
		(
		    
		    /**
		     * Guest
		     * 
		     * Allow: View all public categories, forums, threads, posts
		     */
		    'guest.view_forum_category' => array
		    (
		        'role'      => 'guest',
		        'resource'  => 'forum_category',
		        'privilege' => 'view',
		    ),
		    
		    'guest.view_forum' => array
		    (
		        'role'      => 'guest',
		        'resource'  => 'forum',
		        'privilege' => 'view',
		        'assertion' => array('Acl_Forum_Public')
		    ),
		    
		    'guest.view_forum_thread' => array
		    (
		        'role'      => 'guest',
		        'resource'  => 'forum_thread',
		        'privilege' => 'view',
		        'assertion' => array('Acl_Forum_Public')
		    ),
		    
		    'guest.view_forum_post' => array
		    (
		        'role'      => 'guest',
		        'resource'  => 'forum_post',
		        'privilege' => 'view',
		    ),
		    
		    
		    /**
		     * User
		     * 
		     * Allow: View threads/posts in private forums with membership
		     * Allow: Create threads/posts in private forums with membership
		     * Allow: Edit own threads/posts
		     */
		    'user.view_forum' => array
		    (
		        'role'      => 'user',
		        'resource'  => 'forum',
		        'privilege' => 'view',
		        'assertion' => array('Acl_Forum_Access')
		    ),
		    
		    'user.view_thread' => array(
		        'role'      => 'user',
		        'resource'  => 'forum_thread',
		        'privilege' => 'view',
		        'assertion' => array('Acl_Forum_Access')
		    ),
		    
		    'user.create_thread' => array
		    (
		        'role'      => 'user',
		        'resource'  => 'forum',
		        'privilege' => 'create_thread',
		        'assertion' => array('Acl_Forum_Access')
		    ),
		    
		    'user.reply_thread' => array(
		        'role'      => 'user',
		        'resource'  => 'forum_thread',
		        'privilege' => 'reply',
		        'assertion' => array('Acl_Forum_Access')
		    ),
		    
		    'user.edit_thread' => array(
		        'role'      => 'user',
		        'resource'  => 'forum_thread',
		        'privilege' => 'edit',
		        'assertion' => array('Acl_Assert_Argument', array('id' => 'author_id'))
		    ),
		    
		    'user.edit_post' => array(
		        'role'      => 'user',
		        'resource'  => 'forum_post',
		        'privilege' => 'edit',
		        'assertion' => array('Acl_Assert_Argument', array('id' => 'author_id'))
		    ),
		    
		    
		    /**
		     * Moderator
		     * 
		     * Allow: Edit all threads/posts that it can also view
		     * Allow: Delete all threads/posts that it can also view
		     */
		    'moderator.edit_thread' => array(
		        'role'      => 'moderator',
		        'resource'  => 'forum_thread',
		        'privilege' => 'edit',
		        'assertion' => array('Acl_Forum_Access')
		    ),
		    
		    'moderator.edit_post' => array(
		        'role'      => 'moderator',
		        'resource'  => 'forum_post',
		        'privilege' => 'edit',
		        'assertion' => array('Acl_Forum_Access')
		    ),
		    
		    'moderator.delete_thread' => array(
		        'role'      => 'moderator',
		        'resource'  => 'forum_thread',
		        'privilege' => 'delete',
		        'assertion' => array('Acl_Forum_Access')
		    ),
		    
		    'moderator.delete_post' => array(
		        'role'      => 'moderator',
		        'resource'  => 'forum_post',
		        'privilege' => 'delete',
		        'assertion' => array('Acl_Forum_Access')
		    ),
		    
		    
		    /**
		     * Admin
		     * Allow: View/create/edit/delete categories/forums/threads/posts
		     * Allow: View/create/Edit/Delete/Manage Groups
		     */
		    'admin.forum_category' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_category',
		        'privilege' => array('view', 'create', 'edit', 'delete', 'create_forum'),
		    ),
		    
		    'admin.forum' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum',
		        'privilege' => array('view', 'edit', 'delete', 'create_thread'),
		    ),
		    
		    'admin.forum_thread' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_thread',
		        'privilege' => array('view', 'edit', 'delete', 'reply'),
		    ),
		    
		    'admin.forum_post' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_post',
		        'privilege' => array('view', 'edit', 'delete'),
		    ),
		    
		    'admin.forum_group' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_group',
		        'privilege' => array('view', 'create', 'edit', 'delete', 'manage'),
		    ),
		     
		     
		    /*'admin.view_forum_category' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_category',
		        'privilege' => 'view',
		    ),
		    
		    'admin.view_forum' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum',
		        'privilege' => 'view',
		    ),
		    
		    'admin.view_thread' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_thread',
		        'privilege' => 'view',
		    ),
		    
		    'admin.view_post' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_post',
		        'privilege' => 'view',
		    ),
		    
		    'admin.create_forum_category' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_category',
		        'privilege' => 'create',
		    ),
		    
		    'admin.create_forum' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum',
		        'privilege' => 'create',
		    ),
		    
		    'admin.create_forum_thread' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_thread',
		        'privilege' => 'create',
		    ),
		    
		    'admin.reply_thread' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_thread',
		        'privilege' => 'reply',
		    ),
		    
		    'admin.edit_forum_category' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_category',
		        'privilege' => 'edit',
		    ),
		    
		    'admin.edit_forum' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum',
		        'privilege' => 'edit',
		    ),
		    
		    'admin.edit_thread' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_thread',
		        'privilege' => 'edit',
		    ),
		    
		    'admin.edit_post' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_post',
		        'privilege' => 'edit',
		    ),
		    
		    'admin.delete_forum_category' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_category',
		        'privilege' => 'delete',
		    ),
		    
		    'admin.delete_forum' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum',
		        'privilege' => 'delete',
		    ),
		    
		    'admin.delete_forum_thread' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_thread',
		        'privilege' => 'delete',
		    ),
		    
		    'admin.delete_forum_post' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_post',
		        'privilege' => 'delete',
		    ),
		    
		    'admin.create_group' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_group',
		        'privilege' => 'create',
		    ),
		    
		    'admin.edit_group' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_group',
		        'privilege' => 'edit',
		    ),
		    
		    'admin.delete_group' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_group',
		        'privilege' => 'delete',
		    ),
		    
		    'admin.view_group' => array(
		        'role'      => 'admin',
		        'resource'  => 'forum_group',
		        'privilege' => 'view',
		    ),*/
			
			
			/*
			 * ADD YOUR OWN ALLOW RULES HERE 
			 *
			'ruleName1' => array(
				'role'      => 'guest',
				'resource'  => 'blog',
				'privilege' => 'read'
			),
			'ruleName2' => array(
				'role'      => 'admin'
			),
			'ruleName3' => array(
				'role'      => array('user','manager'),
				'resource'  => 'blog',
				'privilege' => array('delete','edit')
			)
			 */
		),
		'deny' => array
		(
			// ADD YOUR OWN DENY RULES HERE
			/*'registration' => array(
				'role'      => 'user',
				'resource'  => 'user',
				'privilege' => 'register'
			),
			'registration' => array(
				'role'      => 'student',
				'resource'  => 'user',
				'privilege' => 'register'
			),
			'registration' => array(
				'role'      => 'moderator',
				'resource'  => 'user',
				'privilege' => 'register'
			),
			'registration' => array(
				'role'      => 'admin',
				'resource'  => 'user',
				'privilege' => 'register'
			),*/
		)
	)
);