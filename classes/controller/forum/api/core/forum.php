<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forum API Forum Controller
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
class Controller_Forum_Api_Core_Forum extends Controller_Forum_Api_Base {
	
	# The Forum Index page
	public function action_index()
	{
		# Get all the categories
		$categories_raw = ORM::factory('forum_category')->find_all()->as_array();
		
		# Extract only the categories we are allowed to see
		$categories = array();;
		foreach($categories_raw as $category)
		{   
		    # Turn the categories to strings
		    if ($this->a2->allowed($category, 'view'))
		        $categories[] = Request::factory($this->_route->uri(array(
		            'controller' => 'category', 
		            'action' => 'view', 
		            'id' => $category->id
		        )))->execute();
		}
		
		# Prepare the form
		$this->request->response = (string) View::factory('forums/api/html/forum/index')
		    ->set('categories', $categories)
		    ->set('default_route', $this->_front)
		    ->set('forum_title', $this->_config->forum_title)
		    ->set('manage_groups', $this->a2->allowed('forum_group', 'manage'))
		    ->set('new_category', $this->a2->allowed('forum_category', 'create'))
		;
	}
	
	# Make a new forum
	public function action_new($category_id)
	{	
	    # Does the category exist?
	    $category = ORM::factory('forum_category', $category_id);
	    if (! $category->loaded())
	        throw new Kohana_Request_Exception('This page doesn\'t exist.', NULL, 404);
	    
		# Do we have the correct privileges?
		if (! $this->a2->allowed($category, 'create_forum'))
			throw new Kohana_Request_Exception('Not permitted', NULL, 401);
			
		# Prepare the form
		$this->request->response = View::factory('forums/api/html/forum/forum')
			->bind('errors', $errors)
			->bind('data', $data)
		;
		$errors = array();
		$data = array();
				
		# Is there post data?
		if($post = $_POST)
		{
			# Create a new user and fill it with the data from $_POST
			$forum = ORM::factory('forum_forum')->values($post);
			
			# Validate the form
			if ($validation = $forum->check())
			{
			    # Add the category id
			    $forum->category_id = $category->id;
				
				# Validated, save
				$forum->save();
				
				# Ok, we're done here
				$this->request->status = 303;
				return;
			}
			
			# If we get here, there were errors
			$errors = $forum->validate()->errors('forums/forum');
			
			# Set the form data to be post
			$data = $_POST;
		}
	}
	
	# View a forum
	public function action_view($id)
	{
		# Does the forum exist?
		$forum = ORM::factory('forum_forum', $id);
		if (! $forum->loaded())
			throw new Kohana_Request_Exception('This page doesn\'t exist.', Null, 404);
		
		# Check permissions	
		if (! $this->a2->allowed($forum, 'view'))
			throw new Kohana_Request_Exception('Not permitted.', NULL, 401);
		
		# How many threads do we have in this forum?
		$count = $forum->get_thread_count();
		
		# Instantiate the Pagination class and set it up
		$pagination = Pagination::factory();
		$pagination->setup($pagination->config_group('forums'))->total_items = $count;
		
		# Get the threads
		$threads = $forum->threads
		    ->order_by('replied', 'DESC')
		    ->limit($pagination->items_per_page)
		    ->offset($pagination->offset)
		    ->find_all()
		    ->as_array()
		;
	    
		# Convert thread objects to strings
		for ($i = 0, $max = count($threads); $i < $max; $i++)
		{
		    $threads[$i] = Request::factory($this->_route->uri(array(
		        'controller' => 'thread',
		        'action' => 'details', 
		        'id' => $threads[$i]->id
		    )))->execute();
		}
		
		# Build the view with what we've prepared
		$this->request->response = View::factory('forums/api/html/forum/view')
			->set('forum', $forum)
			->set('pagination', $pagination)
			->set('threads', $threads)
			->set('date_format', $this->_config->date_format)
			->set('actions', Request::factory($this->_route->uri(array(
			    'controller' => 'forum', 
			    'action' => 'actions', 
			    'id' => $forum->id
			)))->execute())
		;
	}
	
	# Get the forum details, when we want to see the forum without any of the threads
	public function action_details($id)
	{
		# Does the forum exist?
		$forum = ORM::factory('forum_forum', $id);
		if (! $forum->loaded())
			throw new Kohana_Request_Exception('This page doesn\'t exist.', NULL, 404);
			
		# Check permissions
		if (! $this->a2->allowed($forum, 'view'))
			throw new Kohana_Request_Exception('Not permitted.', NULL, 401);
			
		# Build the response
		$this->request->response = (string) View::factory('forums/api/html/forum/details')
			->set('forum', $forum)
			->set('latest_thread', $forum->get_latest_thread())
			->set('default_route', $this->_front)
			->set('activity', $forum->activity_since_last_visit($this->user))
			->set('actions', Request::factory($this->_route->uri(array(
			    'controller' => 'forum', 
			    'action' => 'actions', 
			    'id' => $forum->id)))
			->execute())
		;
	}
	
	# Edit a forum
	public function action_edit($id)
	{
		# Does the forum exist?
		$forum = ORM::factory('forum_forum', $id);
		if (! $forum->loaded())
			throw new Kohana_Request_Exception('Not found', NULL, 401);
			
		# Do we have the correct privileges?
		if (! $this->a2->allowed($forum, 'edit'))
			throw new Kohana_Request_Exception('Not permitted', NULL, 401);
			
		# Prepare the form
		$this->request->response = View::factory('forums/api/html/forum/forum')
			->bind('errors', $errors)
			->bind('data', $data)
		;
		$errors = array();
		$data = $forum->as_array();
		
		# Is there post data?
		if($post = $_POST)
		{
			# Create a new user and fill it with the data from $_POST
			$forum->values($post);
			
			# Validate the form
			if ($validation = $forum->check())
			{
				
				# Validated, save
				$forum->save();
				
				# Ok, we're done here
				$this->request->status = 303;
				return;
			}
			
			# If we get here, there were errors
			$errors = $forum->validate()->errors('forums/forum');
			
			# Set the form data to be post
			$data = $_POST;
		}
	}
	
	# Delete a forum
	public function action_delete($id)
	{
		# Does the forum exist?
		$forum = ORM::factory('forum_forum', $id);
		if (! $forum->loaded())
			throw new Kohana_Request_Exception('Not found', NULL, 401);
		
		# Do we have the correct privileges?
		if (! $this->a2->allowed($forum, 'delete'))
			throw new Kohana_Request_Exception('Not permitted', NULL, 401);
			
		# Ok, delete it!
		$forum->delete();
		
		# It's finished, we require a redirect
		$this->request->status = 303;
	}
	
	# What actions are the current user allowed to perform on the forum?
	public function action_actions($id)
	{
		# Does the forum exist?
		$forum = ORM::factory('forum_forum', $id);
		if (! $forum->loaded())
			throw new Kohana_Request_Exception('Not found', NULL, 404);
		
		# Build the response
		$this->request->response = (string) View::factory('forums/api/html/forum/actions')
			->set('forum', $forum)
			->set('new_thread_route', Route::get('forums.front.thread.new'))
            ->set('default_route', $this->_front)
			->set('new_thread', $this->a2->allowed($forum, 'create_thread'))
			->set('edit', $this->a2->allowed($forum, 'edit'))
			->set('delete', $this->a2->allowed($forum, 'delete'))
		;
	}
	
}