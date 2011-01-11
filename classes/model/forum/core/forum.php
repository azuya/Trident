<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forum Model
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
class Model_Forum_Core_Forum extends ORM implements Acl_Resource_Interface {
	
	# Relationships
	protected $_belongs_to = array('category' => array('model' => 'forum_category', 'foreign_key' => 'category_id'));
	protected $_has_many = array(
	    'threads' => array('model' => 'forum_thread', 'foreign_key' => 'forum_id'),
	    'forum_groups' => array('model' => 'forum_group', 'through' => 'forum_groups_forums'),
	);
	
	# Validation Filters
	protected $_filters = array(
		TRUE => array(
			'trim' => NULL
		),
	);
	
	# Validation Rules
	protected $_rules = array(
		'title' => array(
			'not_empty'  => NULL,
			'min_length' => array(1),
			'max_length' => array(256),
		),
		'description' => array(
			'not_empty'  => NULL,
		)
	);
	
	# For the ACL config
	public function get_resource_id()
	{
		return 'forum';
	}
	
	# Validation callback to see if a thread exists for a given id
	public static function exists(Validate $array, $field)
	{
		# Run a select query
		$result = DB::select()->from('forum_forums')->where('id', '=', $array[$field])->execute();
		
		# Does it exist?
		if (count($result) === 0)
		{
			# Nope, add an error to the Validate object.
			$array->error($field, 'forum_exists');
		}
	}
	
	# How many threads are there in the forum?
	public function get_thread_count()
	{
		$count = DB::select(DB::expr('COUNT(*) AS mycount'))->from('forum_threads')->where('forum_id', '=', $this->id)->execute()->get('mycount');
		return $count;
	}
	
	# How many posts are in the threads?
	public function get_post_count()
	{
	    $count = DB::select(DB::expr('COUNT(*) AS mycount'))
	        ->from('forum_threads')
	        ->join('forum_posts')
	        ->on('forum_threads.id', '=', 'forum_posts.thread_id')
	        ->where('forum_threads.forum_id', '=', $this->id)
	        ->execute()
	        ->get('mycount')
	    ;
	    return $count;
	}
	
	/** 
	 * This was a very nice, if I don't say so myself, way to get the threads ordered by last updated
	 * without denormalising and adding the replied column to threads as a timestamp for that
	 * 
	 * Because of the way I did threads, this didn't handle properly (first post isn't actually a post,
	 * so threads with no replies that were 'newer' than threads with replies weren't showing up at the top)
	 * 
	 * Since I want to revisit threads without the OP in the thread table, I am going to leave this here for when I do.
	 */
	 
    /*# List of thread ids, ordered by last post in thread (per thread) created desc.
    public function get_thread_ids($limit, $offset)
    {
        /* Our SQL.
           
           We join forum_threads on forum_posts where 
           thread.forum_id is of the forum we're looking for
           
           Then we order by the the latest forum posts in each thread,
           achieveing unique threads by using SELECT DISTINCT
           
           Gives us a list of thread IDs for a page defined by LIMIT and OFFSET
        
         */
        /*$query_str = 'SELECT
                          forum_threads.id
                      FROM
                          forum_threads
                      LEFT JOIN
                          forum_posts
                          ON
                              forum_threads.id = forum_posts.thread_id
                      WHERE
                          forum_threads.forum_id = :forum_id
                      GROUP BY
                          forum_threads.id
                      ORDER BY
                          max(forum_posts.created) DESC
                      LIMIT
                          :limit
                      OFFSET
                          :offset';
		
		$query = DB::query(DATABASE::SELECT, $query_str)
		    ->param(':forum_id', $this->id)
		    ->param(':limit', $limit)
		    ->param(':offset', $offset)
		;
		
		return arr::pluck($query->execute()->as_array(), 'id');
	}*/
	
	# What is the latest thread updated in the forum?
	public function get_latest_thread()
	{
	    # Get the thread
	    $latest_thread = $this->threads->order_by('replied', 'DESC')->limit(1)->find_all();
	    
	    # Return false if no threads, the thread if any
	    return count($latest_thread) === 0 ? FALSE : $latest_thread->current();
	}
	
	# Has there been any activity since the last visit?
	public function activity_since_last_visit($user)
	{
	    # Get the latest thread, we only need to check for activity in it.
	    $latest = $this->get_latest_thread();
	    
	    # Did we even get a thread?
	    if ($latest !== FALSE)
	        # We only need to check the latest thread to see if there has been activity since the last visit.
	        return $latest->activity_since_last_visit($user);
	    
	    # No threads, ergo no activity.
	    return FALSE;
	}
	
	# If it's the first time saving the forum to the database (new forum), then set the timestamp
	public function save()
	{
		if (! $this->_loaded)
		{
			$this->created = time();
		}
		parent::save();
	}
}