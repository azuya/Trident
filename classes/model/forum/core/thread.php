<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forum Thread Model
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
class Model_Forum_Core_Thread extends ORM implements Acl_Resource_Interface {
	
	# Relationships
	protected $_belongs_to = array(
		'forum' => array('model' => 'forum_forum', 'foreign_key' => 'forum_id'),
		'author' => array('model' => 'user', 'foreign_key' => 'author_id'),
	);
	
	protected $_has_many = array(
		'posts' => array('model' => 'forum_post', 'foreign_key' => 'thread_id'),
		'views' => array('model' => 'forum_view', 'foreign_key' => 'thread_id'),
	);
	
	# Validation Filters
	protected $_filters = array(
		TRUE => array(
			'trim' => NULL,
		),
	);
	
	# Validation Rules
	protected $_rules = array(
		'title' => array(
			'not_empty'  => NULL,
			'min_length' => array(1),
			'max_length' => array(256),
		),
		'content' => array(
			'not_empty' => NULL
		),
	);
	
	# For A2 configuration
	public function get_resource_id()
	{
		return 'forum_thread';
	}
	
	# Validation callback to see if a thread exists for a given id
	public static function exists(Validate $array, $field)
	{
		# Run a select query
		$result = DB::select()->from('forum_threads')->where('id', '=', $array[$field])->execute();
		
		# Does it exist?
		if (count($result) === 0)
		{
			# Nope, add an error to the Validate object.
			$array->error($field, 'thread_exists');
		}
	}
	
	# How many replies are there?
	public function get_replies_count()
	{
		$count = DB::select(DB::expr('COUNT(*) AS mycount'))->from('forum_posts')->where('thread_id', '=', $this->id)->execute()->get('mycount');
		return $count;
	}
	
	# How many views are there?
	public function get_views_count()
	{
		$count = DB::select(DB::expr('COUNT(*) AS mycount'))->from('forum_views')->where('thread_id', '=', $this->id)->execute()->get('mycount');
		return $count;
	}
	
	# Get the latest post
	public function get_latest_post()
	{
		$latest = $this->posts->order_by('created', 'desc')->limit(1)->find_all();
		
		# Do we have a post?
		if (count($latest))
		    return $latest->current();
		
		# We don't have any posts, return false
		return FALSE;
	}
	
	# Has there been activity since the last visit for this user?
	public function activity_since_last_visit($user)
	{
		# If there is no user, assume activity
		if ($user === FALSE)
			return TRUE;
		
		# Get the latest post
		$latest_post = $this->get_latest_post();
		
		# Get the latest view for this user
		$latest_view = ORM::factory('forum_view')
			->order_by('timestamp', 'desc')
			->limit(1)
			->where('user_id', '=', $user->id)
			->and_where('thread_id', '=', $this->id)
			->find_all()
		;
		
		# If there are no views then there has been activity..
		if (! count($latest_view))
			return TRUE;
			
		# Take the view out of the result wrapper
		$latest_view = $latest_view->current();
		
		# If latest post is greater than the latest view,
		# then there has been a post since the last time it was views
		# by this user.
		
		# We have to use either the latest post, or thread creation date
		if ($latest_post === FALSE)
		    # No posts
		    return $this->created >= $latest_view->timestamp;
		
		# We have posts, use the latest like expected    
		return $latest_post->created >= $latest_view->timestamp;
	}
	
	# Any special things we have to do on save, such as setting a created or last-modified timestamp
	public function save()
	{
		# In case something takes a very long time.
		$now = time();
		
		# Do we exist already or not?
		if (! $this->loaded())
		{
			# We don't exist yet, it's a new save
			$this->created = $now;
            $this->replied = $now;
			
			# Set the user id
			$this->author_id = A2::instance(Kohana::config('forum/settings')->a2_instance)->get_user()->id;
		}
		
		# We're always going to be setting last modified
		$this->last_modified = $now;
		
		# Continue about your normal business
		parent::save();
	}
	
}