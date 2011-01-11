<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forum API Post Controller
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
class Controller_Forum_Api_Core_Post extends Controller_Forum_Api_Base {
	
	public function action_new($thread_id)
	{
		# Try and create a thread object to see if it exists
		$thread = ORM::factory('forum_thread', $thread_id);
		if (! $thread->loaded())
			throw new Kohana_Request_Exception('This page doesn\'t exist.', NULL, 404);
			
		# Now we can check for permission
		if (! $this->a2->allowed($thread, 'reply'))
			throw new Kohana_Request_Exception('Not permitted.', NULL, 401);
			
		# Prepare the form
		$this->request->response = View::factory('forums/api/html/post/post')->bind('errors', $errors)->bind('data', $data);
		$errors = array();
		$data = array();
		
		# Is there post data?
		if($post = $_POST)
		{
			# Create a new user and fill it with the data from $_POST
			$forum_post = ORM::factory('forum_post')->values($post);
			
			# Validate the form
			if ($forum_post->check())
			{
				# Add the thread id before saving
				$forum_post->thread_id = $thread->id;
				
				# Validated, save
				$forum_post->save();
				
				# Ok we're finished now
				return;
			}
			
			# If we get here, there were errors
			$errors = $forum_post->validate()->errors('forums/post');
			
			# Set the form data to be post
			$data = $_POST;
		}
	}
	
	# View a post given an id
	public function action_view($id)
	{
		# Does the post exist?
		$post = ORM::factory('forum_post', $id);
		if (! $post->loaded())
			throw new Kohana_Request_Exception('This page doesn\'t exist.', Null, 404);
		
		# Are we allowed to see it?	
		if (! $this->a2->allowed($post, 'view'))
			throw new Kohana_Request_Exception('Not permitted.', NULL, 401);
		
		# Build the response
		$this->request->response = View::factory('forums/api/html/post/view')
			->set('post', $post)
			->set('date_format', $this->_config->date_format)
			->set('actions', Request::factory($this->_route->uri(array(
			    'controller' => 'post',
			    'action' => 'actions',
			    'id' => $post->id
			)))->execute())
		;
	}
	
	# Edit a post given an id
	public function action_edit($id)
	{
		# Does the post exist?
		$forum_post = ORM::factory('forum_post', $id);
		if (! $forum_post->loaded())
			throw new Kohana_Request_Exception('This page doesn\'t exist.', NULL, 404);
			
		# Now we can check for permission
		if (! $this->a2->allowed($forum_post, 'edit'))
			throw new Kohana_Request_Exception('Not permitted.', NULL, 401);
			
		# Prepare the form
		$this->request->response = View::factory('forums/api/html/post/post')->bind('errors', $errors)->bind('data', $data);
		$errors = array();
		$data = $forum_post->as_array();
		
		# Is there post data?
		if($post = $_POST)
		{
			# Create a new user and fill it with the data from $_POST
			$forum_post->values($post);
			
			# Validate the form
			if ($forum_post->check())
			{
				# Validated, save
				$forum_post->save();
				
				# Ok we're finished now
				return;
			}
			
			# If we get here, there were errors
			$errors = $forum_post->validate()->errors('forums/post');
			
			# Set the form data to be post
			$data = $_POST;
		}
	}
	
	# Delete a post given an id
	public function action_delete($id)
	{
		# Does the post exist?
		$forum_post = ORM::factory('forum_post', $id);
		if (! $forum_post->loaded())
			throw new Kohana_Request_Exception('Not found', NULL, 404);
		
		# Do we have the correct privileges?
		if (! $this->a2->allowed($forum_post, 'delete'))
			throw new Kohana_Request_Exception('Not permitted', NULL, 401);
			
		# Ok, delete it!
		$forum_post->delete();
	}
	
	# What actions are the current user allowed to perform on the post?
	public function action_actions($id)
	{
		# Does the post exist?
		$post = ORM::factory('forum_post', $id);
		if (! $post->loaded())
			throw new Kohana_Request_Exception('Not found', NULL, 404);
			
		$this->request->response = (string) View::factory('forums/api/html/post/actions')
			->set('post', $post)
			->set('default_route', $this->_front)
			->set('edit', $this->a2->allowed($post, 'edit'))
			->set('delete', $this->a2->allowed($post, 'delete'))
		;
	}
	
}