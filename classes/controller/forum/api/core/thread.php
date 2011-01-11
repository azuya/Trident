<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forum API Thread Controller
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
class Controller_Forum_Api_Core_Thread extends Controller_Forum_Api_Base {
	
	public function action_new($forum_id)
	{		
		#  Try and create a forum object to see if it exists
		$forum = ORM::factory('forum_forum', $forum_id);
		if (! $forum->loaded())
			throw new Kohana_Request_Exception('This page doesn\'t exist.', NULL, 404);
			
		#  Now we can check for permission
		if (! $this->a2->allowed($forum, 'create_thread'))
			throw new Kohana_Request_Exception('Not permitted.', NULL, 401);
			
		# Prepare the form
		$this->request->response = View::factory('forums/api/html/thread/thread')->bind('errors', $errors)->bind('data', $data);
		$errors = array();
		$data = array();
		
		# Is there post data?
		if($post = $_POST)
		{	
			# Create a new thread and fill it with the data from $_POST
			$thread = ORM::factory('forum_thread')->values($post);
			
			# Validate the form
			if ($thread->check())
			{	
				# Add the forum id before saving
				$thread->forum_id = $forum->id;
				
				# Validated, save.
				$thread->save();
				
				# Ok we're finished now
				return;
			}
			
			# If we get here, there were errors
			$errors = $thread->validate()->errors('forums/thread');
			
			# Set the form data to be post
			$data = $_POST;
		}
	}
	
	# View a thread given an id.
	public function action_view($id)
	{
	    //TODO: ?page=last
	    
		# Does the thread exist?
		$thread = ORM::factory('forum_thread', $id);
		if (! $thread->loaded())
			throw new Kohana_Request_Exception('This page doesn\'t exist.', Null, 404);
		
		# Check permissions	
		if (! $this->a2->allowed($thread, 'view'))
			throw new Kohana_Request_Exception('Not permitted.', NULL, 401);
		
		# How many replies do we have in this thread?
		$count = $thread->get_replies_count();
		
		# Instantiate the Pagination class and set it up
		$pagination = Pagination::factory();
		$pagination->setup($pagination->config_group('forums'))->total_items = $count + 1;
		
		# Cos of the tomfoolery with thread having an opening post and also having posts attached as replies, 
		# we need to muck around with pagination a bit.
		$limit = $pagination->items_per_page;
		$offset = $pagination->offset;
		
		# If we're on the 1st page, need 1 less item on the page
		if ($pagination->current_page === 1)
			$limit--;
		# If we're on any other page, need to offset the offset (heh) by 1.
		else
			$offset--;
		
		# Build up the page of posts
		$posts = $thread->posts->limit($limit)->offset($offset)->find_all()->as_array();
		
		# Convert post objects to strings
		for ($i = 0, $max = count($posts); $i < $max; $i++)
			$posts[$i] = Request::factory($this->_route->uri(array(
			    'controller' => 'post',
			    'action' => 'view', 
			    'id' => $posts[$i]->id)))
			->execute();
		
		# Build the view with what we've prepared
		$this->request->response = View::factory('forums/api/html/thread/view')
			->set('thread', $thread)
			->set('pagination', $pagination)
			->set('posts', $posts)
			->set('date_format', $this->_config->date_format)
			->set('actions', Request::factory($this->_route->uri(array(
                'controller' => 'thread',
                'action' => 'actions',
                'id' => $thread->id,
			)))->execute())
		;
		
		# Now Add a forum_view to the database
		$view = ORM::factory('forum_view')->values(array('user_id' => ($this->user ? $this->user->id : NULL), 'thread_id' => $thread->id))->save();
		
	}
	
	# Get the thread details, when we want to see the thread without any of the posts
	public function action_details($id)
	{
		# Does the thread exist?
		$thread = ORM::factory('forum_thread', $id);
		if (! $thread->loaded())
			throw new Kohana_Request_Exception('This page doesn\'t exist.', NULL, 404);
			
		# Check permissions
		if (! $this->a2->allowed($thread, 'view'))
			throw new Kohana_Request_Exception('Not permitted.', NULL, 401);
		
		# Build the response
		$this->request->response = (string) View::factory('forums/api/html/thread/details')
			->set('thread', $thread)
			->set('latest_post', $thread->get_latest_post())
			->set('default_route', $this->_front)
			->set('activity', $thread->activity_since_last_visit($this->user))
			->set('date_format', $this->_config->date_format)
			->set('actions', Request::factory($this->_route->uri(array(
			    'controller' => 'thread', 
			    'action' => 'actions', 
			    'id' => $thread->id
			)))->execute())
		;
	}
	
	# Delete a thread given an id.
	public function action_edit($id)
	{
		# We need to know if it exists before anything.
		$thread = ORM::factory('forum_thread', $id);
		if (! $thread->loaded())
			throw new Kohana_Request_Exception('This page doesn\'t exist.', NULL, 404);
			
		# Now we can check for permission
		if (! $this->a2->allowed($thread, 'edit'))
			throw new Kohana_Request_Exception('Not permitted.', NULL, 401);
			
		# Prepare the form
		$this->request->response = View::factory('forums/api/html/thread/thread')->bind('errors', $errors)->bind('data', $data);
		$errors = array();
		$data = $thread->as_array();
		
		# Is there post data?
		if($post = $_POST)
		{	
			# Create a new thread and fill it with the data from $_POST
			$thread->values($post);
			
			# Validate the form
			if ($thread->check())
			{	
				# Validated, save.
				$thread->save();
				
				# Ok we're finished now
				return;
			}
			
			# If we get here, there were errors
			$errors = $thread->validate()->errors('forums/thread');
			
			# Set the form data to be post
			$data = $_POST;
		}
	}
	
	# Delete a thread given an id.
	public function action_delete($id)
	{
		# Does the thread exist?
		$thread = ORM::factory('forum_thread', $id);
		if (! $thread->loaded())
			throw new Kohana_Request_Exception('Not found', NULL, 401);
		
		# Do we have the correct privileges?
		if (! $this->a2->allowed($thread, 'delete'))
			throw new Kohana_Request_Exception('Not permitted', NULL, 401);
			
		# Ok, delete it!
		$thread->delete();
	}
	
	# What actions are the current user allowed to perform on the thread?
	public function action_actions($id)
	{
		# Does the thread exist?
		$thread = ORM::factory('forum_thread', $id);
		if (! $thread->loaded())
			throw new Kohana_Request_Exception('Not found', NULL, 404);
		
		# Build the response
		$this->request->response = (string) View::factory('forums/api/html/thread/actions')
			->set('thread', $thread)
			->set('reply_route', Route::get('forums.front.post.new'))
			->set('default_route', $this->_front)
			->set('reply', $this->a2->allowed($thread, 'reply'))
			->set('edit', $this->a2->allowed($thread, 'edit'))
			->set('delete', $this->a2->allowed($thread, 'delete'))
		;
	}
	
}