<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Publicly Viewable ACL Assertion Class
 *
 * An assertion class for the forums module that will determine if a resource is publicly viewable
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
Class Acl_Forum_Access implements Acl_Assert_Interface {
    
    /**
     * @var  Array  Arguments passed in the constructor for the assertion
     */
    protected $_arguments;
    
    /**
     * Constructor function
     * 
     * Assigns the arguments for the assertion to the internal var
     *
     * @return  Acl_Forums_Public
     */
    public function __construct($arguments = NULL)
	{
		$this->_arguments = $arguments;
	}
	
    /**
     * Assert function
     *
     * Checks to see if the user has access to the resource through a group
     *
     * @param   Acl  $acl
     * @param   mixed  $role  
     * @param   mixed  $resource  
     * @param   mixed  $privilege    
     */
	public function assert(Acl $acl, $role = null, $resource = null, $privilege = null)
	{   
	    # If it's a post, make it a thread
	    if ($resource->get_resource_id() === 'forum_post')
	    {
	        $resource = $resource->thread;
	    }
	    
	    # If it's a thread, make it a forum
	    if ($resource->get_resource_id() === 'forum_thread')
	    {
	        $resource = $resource->forum;
	    }
	    
	    # Get all the groups with access to the forum
	    $groups = $resource->forum_groups->find_all();
	    foreach($groups as $group)
	    {
	        # Is the user a member of the group?
	        if ($group->has('users', $role))
	            return TRUE;
	    }
	    
	    # If we finished the loop, none of the groups had the user, deny.
	    return FALSE;
	}
    
}