<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forum Category Model
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
class Model_Forum_Core_Category extends ORM implements Acl_Resource_Interface {
	
	protected $_has_many = array(
	    'forums' => array('model' => 'forum_forum', 'foreign_key' => 'category_id')
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
	);
	
	# For the ACL config
	public function get_resource_id()
	{
		return 'forum_category';
	}
	
	# Validation callback to see if a category exists for a given id
	public static function exists(Validate $array, $field)
	{
		# Run a select query
		$result = DB::select()->from('forum_categories')->where('id', '=', $array[$field])->execute();
		
		# Does it exist?
		if (count($result) === 0)
		{
			# Nope, add an error to the Validate object.
			$array->error($field, 'category_exists');
		}
	}
	
}