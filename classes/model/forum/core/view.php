<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forum View Model
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
class Model_Forum_Core_View extends ORM implements Acl_Resource_Interface {

	# Relationships
	protected $_belongs_to = array(
		'thread' => array('model' => 'forum_thread', 'foreign_key' => 'thread_id'),
		'user' => array('model' => 'user', 'foreign_key' => 'author_id')
	);
	
	# For the A2 configuration
	public function get_resource_id()
	{
		return 'forum_view';
	}
	
	# Set a timestamp on a new save
	public function save()
	{
		# If it's a first-time save
		if (! $this->loaded())
			$this->timestamp = time();
		
		# Continue about your normal business
		parent::save();
	}
}