<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forum API Category Controller
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
class Controller_Forum_Api_Core_Category extends Controller_Forum_Api_Base {
	
	# Create a new category
	public function action_new()
	{
		# Do we have the correct privileges?
		if (! $this->a2->allowed('forum_category', 'create'))
			throw new Kohana_Request_Exception('Not permitted', NULL, 401);
			
		# Prepare the form
		$this->request->response = View::factory('forums/api/html/category/category')->bind('errors', $errors)->bind('data', $data);
		$errors = array();
		$data = array();
		
		# Is there post data?
		if($post = $_POST)
		{
			# Create a new user and fill it with the data from $_POST
			$category = ORM::factory('forum_category')->values($post);
			
			# Validate the form
			if ($category->check())
			{
				
				# Validated, save and redirect to the home page
				$category->save();
				
				# Ok we're finished now. Signal a redirect.
				$this->request->status = 303;
				return;
			}
			
			# If we get here, there were errors
			$errors = $category->validate()->errors('forums/category');
			
			# Set the form data to be post
			$data = $_POST;
		}
	}
	
	# View a category given an id
	public function action_view($id)
	{
	    # Does the category exist?
		$category = ORM::factory('forum_category', $id);
		if (! $category->loaded())
			throw new Kohana_Request_Exception('This page doesn\'t exist.', NULL, 404);
		
		# Check permissions	
		if (! $this->a2->allowed($category, 'view'))
			throw new Kohana_Request_Exception('Not permitted.', NULL, 401);
		
		# Get the forums for this category
		$forums = $category->forums->find_all()->as_array();
		
		# Convert forum objects to strings with the forum details route
		for ($i = 0, $max = count($forums); $i < $max; $i++)
		{
		    $content = '';
		    
		    # We need to catch 401 errors and just blank the response out
		    # rather than passing them along
		    try {
		        $content = Request::factory($this->_route->uri(array(
		            'controller' => 'forum', 
		            'action' => 'details', 
		            'id' => $forums[$i]->id
		        )))->execute();
		        
		    } catch (Kohana_Request_Exception $e) {
		        
		        if ($e->getCode() != 401)
		            throw $e;
		    }
		    
		    $forums[$i] = $content;
		}
		
		#No forums, return now with a blank response
		/*if (count($forums) === 0)
		{
		    $this->request->response = '';
		    return;
		}*/
		
		# Build the view with what we've prepared
		$this->request->response = View::factory('forums/api/html/category/view')
			->set('category', $category)
			->set('forums', $forums)
			->set('actions', Request::factory($this->_route->uri(array(
			    'controller' => 'category', 
			    'action' => 'actions', 
			    'id' => $category->id
			)))->execute())
		;
	}
	
	# Edit an existing category
	public function action_edit($id)
	{
		# Does the category exist?
		$category = ORM::factory('forum_category', $id);
		if (! $category->loaded())
			throw new Kohana_Request_Exception('Not found', NULL, 401);
		
		# Do we have the correct privileges?
		if (! $this->a2->allowed($category, 'edit'))
			throw new Kohana_Request_Exception('Not permitted', NULL, 401);
			
		# Prepare the form
		$this->request->response = View::factory('forums/api/html/category/category')->bind('errors', $errors)->bind('data', $data);
		$errors = array();
		$data = $category->as_array();
		
		# Is there post data?
		if($post = $_POST)
		{
			# Fill the category with the data from $_POST
			$category->values($post);
			
			# Validate the form
			if ($category->check())
			{
				
				# Validated, save and redirect to the home page
				$category->save();
				
				# Ok we're finished now. Signal a redirect
				$this->request->status = 303;
				return;
			}
			
			# If we get here, there were errors
			$errors = $category->validate()->errors('forums/category');
			
			# Set the form data to be post
			$data = $_POST;
		}
	}
	
	# Delete a category
	public function action_delete($id)
	{
		# Does the category exist?
		$category = ORM::factory('forum_category', $id);
		if (! $category->loaded())
			throw new Kohana_Request_Exception('Not found', NULL, 401);
		
		# Do we have the correct privileges?
		if (! $this->a2->allowed($category, 'delete'))
			throw new Kohana_Request_Exception('Not permitted', NULL, 401);
			
		# Ok, delete it!
		$category->delete();
		
		# We're done, signal a redirect
		$this->request->status = 303;
	}
	
	# What actions are the current user allowed to perform on the category?
	public function action_actions($id)
	{
		# Does the category exist?
		$category = ORM::factory('forum_category', $id);
		if (! $category->loaded())
			throw new Kohana_Request_Exception('Not found', NULL, 404);
		
		# Build the response
		$this->request->response = (string) View::factory('forums/api/html/category/actions')
			->set('category', $category)
			->set('default_route', $this->_front)
			->set('new_forum_route', Route::get('forums.front.forum.new'))
			->set('new_forum', $this->a2->allowed($category, 'create_forum'))
			->set('edit', $this->a2->allowed($category, 'edit'))
			->set('delete', $this->a2->allowed($category, 'delete'))
		;
	}
	
}