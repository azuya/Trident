<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forum Post Model
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
class Model_Forum_Core_Post extends ORM implements Acl_Resource_Interface {

	# Relationships
	protected $_belongs_to = array(
		'thread' => array('model' => 'forum_thread', 'foreign_key' => 'thread_id'),
		'author' => array('model' => 'user', 'foreign_key' => 'author_id')
	);
	
	# Validation Filters
	protected $_filters = array(
		TRUE => array(
			'trim' => NULL,
		),
	);
	
	# Validation Rules
	protected $_rules = array(
		'content' => array('not_empty' => NULL),
	);
	
	public function get_resource_id()
	{
		return 'forum_post';
	}
	
	# Any special things we have to do on save, such as setting a created or last-modified timestamp
	public function save()
	{
		# In case something takes a very long time.
		$now = time();
		
		# We need this for later
		$first_time = (! $this->loaded());
		# Do we exist already or not?
		if (! $this->loaded())
		{
			# We don't exist yet, it's a new save
			$this->created = $now;
			
			# Set the user id
			$this->author_id = A2::instance(Kohana::config('forum/settings')->a2_instance)->get_user()->id;
		}
		
		# We're always going to be setting last modified
		$this->last_modified = $now;
		
		# Continue about your normal business
		parent::save();
		
		# More first time stuff, just needed the save to go through first.
		if ($first_time)
		{
		    $this->thread->replied = $now;
		    $this->thread->save();
		}
	}
}