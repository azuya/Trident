<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forum Group Controller
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
class Controller_Forum_Api_Core_Group extends Controller_Forum_Api_Core_Base {
    
    /**
     * Groups Index Action
     * 
     * Shows a list of groups, link to create new groups
     */
    public function action_index()
    {
        # Do we have permission to manage groups?
        if (! $this->a2->allowed('forum_group', 'manage'))
            throw new Kohana_Request_Exception('Not permitted.', NULL, 401);
            
        # Get all the groups
        $groups = ORM::factory('forum_group')->find_all()->as_array();
        
        # Turn the groups into detail strings via requests
		for ($i = 0, $max = count($groups); $i < $max; $i++)
		{
		    $groups[$i] = Request::factory($this->_route->uri(array(
		        'controller' => 'group',
		        'action' => 'details', 
		        'id' => $groups[$i]->id
		    )))->execute();
		}
        
        $this->request->response = View::factory('forums/api/html/group/index')
            ->set('groups', $groups)
            ->set('default_route', $this->_front)
            ->set('new_group', $this->a2->allowed('forum_group', 'manage'))
        ;
    }
    
    /**
     * Groups Details Action
     * 
     * Shows the details of a single group, along with its actions
     */
    public function action_details($id)
    {
        # Does the group exist?
        $group = ORM::factory('forum_group', $id);
        if (! $group->loaded())
            throw new Kohana_Request_Exception('This page doesn\'t exist.', NULL, 404);
        
        # Do we have permission?
        if (! $this->a2->allowed($group, 'manage'))
            throw new Kohana_Request_Exception('Not permitted.', NULL, 401);
            
        $this->request->response = View::factory('forums/api/html/group/details')
            ->set('group', $group)
            ->set('actions', Request::factory($this->_route->uri(array(
                'controller' => 'group',
                'action' => 'actions',
                'id' => $group->id
            )))->execute());
        ;
    }
    
    /**
     * New Group Action
     *
     * Shows a form for making a new forum group, also handles
     * the POST request from that form.
     *
     * @return  void
     */
    public function action_new()
    {
        # Check Permissions
        if (! $this->a2->allowed('forum_group', 'create'))
            throw new Kohana_Request_Exception('Not permitted', NULL, 401);
            
        # Prepare the form
		$this->request->response = View::factory('forums/api/html/group/group')->bind('errors', $errors)->bind('data', $data);
		$errors = array();
		$data = array();
		
		# Is there post data?
		if($post = $_POST)
		{
			# Create a new group and fill it with the data from $_POST
			$group = ORM::factory('forum_group')->values($post);
			
			# Validate the form
			if ($group->check())
			{	
				# Validated, save and redirect to the home page
				$group->save();
				
				# Ok we're finished now, signal a redirect
				$this->request->status = 303;
				return;
			}
			
			# If we get here, there were errors
			$errors = $group->validate()->errors('forums/group');
			
			# Set the form data to be post
			$data = $_POST;
		}
    }
    
    /**
     * Edit Group Action
     *
     * Edits a group, handles showing the form and
     * dealing with the POST request from that form
     *
     * @param   String  $id  ID of the group to edit
     * @return  void
     */
    public function action_edit($id)
    {
        #Does the group exist?
        $group = ORM::factory('forum_group', $id);
        if (! $group->loaded())
            throw new Kohana_Request_Exception('This page doesn\'t exist.', NULL, 404);
            
        #Are we allowed to edit?
        if (! $this->a2->allowed($group, 'edit'))
            throw new Kohana_Request_Exception('Not permitted.', NULL, 401);
        
        # Prepare the form
		$this->request->response = View::factory('forums/api/html/group/group')->bind('errors', $errors)->bind('data', $data);
		$errors = array();
		$data = $group->as_array();
		
		# Is there post data?
		if($post = $_POST)
		{
			# fill the group with the data from $_POST
			$group->values($post);
			
			# Validate the form
			if ($group->check())
			{
				
				# Validated, save and redirect to the home page
				$group->save();
				
				# Ok we're finished now, signal a redirect
				$this->request->status = 303;
				return;
			}
			
			# If we get here, there were errors
			$errors = $group->validate()->errors('forums/group');
			
			# Set the form data to be post
			$data = $_POST;
		}
        
    }
    
    /**
     * Delete Group Action
     *
     * Deletes the group with the given id
     *
     * @param   String  $id  ID of the group to delete
     * @return  void
     */
    public function action_delete($id)
    {
        # Does the group exist?
		$group = ORM::factory('forum_group', $id);
		if (! $group->loaded())
			throw new Kohana_Request_Exception('Not found', NULL, 401);
		
		# Do we have the correct privileges?
		if (! $this->a2->allowed($group, 'delete'))
			throw new Kohana_Request_Exception('Not permitted', NULL, 401);
			
		# Ok, delete it!
		$group->delete();
		
		# Signal a redirect
		$this->request->status = 303;
    }
    
    /**
     * Actions Group Action
     *
     * Returns a list of actions that the users is allowed
     * to perform on the group, with links to performing
     * those actions provided
     *
     * @param   String  $id  ID of the group to get available actions for
     * @return  void
     */
    public function action_actions($id)
    {
        # Does the group exist?
		$group = ORM::factory('forum_group', $id);
		if (! $group->loaded())
			throw new Kohana_Request_Exception('Not found', NULL, 404);
		
		# Build the response
		$this->request->response = (string) View::factory('forums/api/html/group/actions')
			->set('group', $group)
			->set('default_route', $this->_front)
			->set('edit', $this->a2->allowed($group, 'edit'))
			->set('delete', $this->a2->allowed($group, 'delete'))
			->set('assign', $this->a2->allowed($group, 'manage'))
		;
    }
    
    /**
     * Group Forums Action
     * 
     * This page shows a list of forums that the group is privvy to.
     * Users with the correct permissions can edit which forums groups
     * have access to.
     *
     * @param   String  $id  ID of the group to see available forums
     * @return  void
     */
    public function action_forums($id)
    {
        # Does the group exist?
		$group = ORM::factory('forum_group', $id);
		if (! $group->loaded())
			throw new Kohana_Request_Exception('Not found', NULL, 404);
		
		# Do we have the correct privileges?
		if (! $this->a2->allowed($group, 'manage'))
			throw new Kohana_Request_Exception('Not permitted', NULL, 401);
		
		#Get the list of forums
		$forums = ORM::factory('forum_forum')->find_all();
		
		#Prepare the page
		$this->request->response = View::factory('forums/api/html/group/forums')
		    ->bind('errors', $errors)
		    ->bind('data', $data)
		    ->bind('group', $group)
		    ->bind('forums', $forums)
		;
		$errors = array();
		$data = array();
		
		# Is there post data?
		if($post = $_POST)
		{
		    # Make the relationships
		    $group->set_forums(arr::get($post, 'forums', array()));
			
			# Set the data for the form to reflect what was posted
			$data = $_POST;
		}
    }
    
    /**
	 * View and modify the groups a user is a part of
	 *
	 * @param   String  $user_id  The id of the user
	 * @return  void
	 */
	public function action_user($user_id)
	{
	    # Does the user exist?
		$user = ORM::factory('user', $user_id);
		if (! $user->loaded())
			throw new Kohana_Request_Exception('Not found', NULL, 404);
		
		# Do we have the correct privileges?
		if (! $this->a2->allowed('forum_group', 'manage'))
			throw new Kohana_Request_Exception('Not permitted', NULL, 401);
		
		# Get all the groups
		$groups = ORM::factory('forum_group')->find_all();
		
		# Prepare the page
		$this->request->response = View::factory('forums/api/html/group/user')
		    ->bind('errors', $errors)
		    ->bind('data', $data)
		    ->bind('user', $user)
		    ->bind('groups', $groups)
		;
		$errors = array();
		$data = array();
		
		# Is there post data?
		if ($post = $_POST)
		{
		    /**
		     * We use a static method of the Group model so we can keep
		     * loose coupling with non forums module classes. As in, since
		     * we can't add methods to the user model, we have to put this
		     * one in a slightly less intuitive location
		     */
		     
		    # Make the relationships
		    Model_Forum_Group::set_user_groups($user_id, arr::get($post, 'groups', array()));
		    
		    # Set the data for the form to reflect what was posted
		    $data = $_POST;
		}
	}
}