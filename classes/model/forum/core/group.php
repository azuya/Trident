<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forum Group Model
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
class Model_Forum_Core_Group extends ORM implements Acl_Resource_Interface {

    /**
     * @var  Array  List of validation filters  
     */
    protected $_filters = array(
        TRUE => array(
            'trim' => NULL,
        ),
    );
    
    /**
     * @var  Array  List of validation rules
     */
    protected $_rules = array(
		'title' => array(
			'not_empty'  => NULL,
			'min_length' => array(1),
			'max_length' => array(256),
		),
	);
    
    /**
     * @var  Array  List of validation callbacks
     */
    #Validation Callbacks
	protected $_callbacks = array(
		'title' => array(
			'title_available'
		),
	);
    
    /**
     * @var  Array  Has Many relationships
     */
    protected $_has_many = array(
        'users'  => array('model' => 'user', 'through' => 'forum_groups_users'),
        'forum_forums' => array('model' => 'forum_forum', 'through' => 'forum_groups_forums'),
    );
    
    /**
     * Required by Acl_Resource_Interface, returns a resource string. Used
     * in a2 configuration.
     * 
     * @return  string  Resource ID
     */
    public function get_resource_id()
	{
		return 'forum_group';
	}
	
	/**
	 * Returns the number of forums assigned to the group
	 * 
	 * @return  int  Number of forums
	 */
	public function get_forum_count()
	{
	    //TODO: Actually do this, should be simple
	    return 1;
	}
	
	/**
	 * Returns the number of users assigned to the group
	 * 
	 * @return  int  Number of users
	 */
	public function get_user_count()
	{
	    //TODO: Actually do this, should be simple
	    return 1;
	}
	
	/**
	 * Sets the forums that this group has access to
	 * 
	 * @param   Array  $ids  An array of forum ids to set the group to owning
	 * @throws  Kohana_Request_Exception
	 * @return  void
	 */
	public function set_forums(Array $ids)
	{
	    # Start the transaction
	    DB::QUERY(NULL, 'BEGIN')->execute();
	    
	    # How many relationships exists already?
	    $count = DB::select(DB::expr('COUNT(*) AS mycount'))->from('forum_groups_forums')->where('forum_group_id', '=', $this->id)->execute()->get('mycount');
	    
	    # Clear the relationships for this group
	    $rows_deleted = DB::delete('forum_groups_forums')->where('forum_group_id', '=', $this->id)->execute();
	    
	    # Were there any to delete and if so, were they deleted?
	    if ($count > 0 AND $rows_deleted != $count)
	    {
	        # Not all cleared, roll the transaction
	        DB::QUERY(NULL, 'ROLLBACK')->execute();
	        
	        # Throw a database error.
	        throw new Kohana_Request_Exception('Database error.', NULL, 500);
	    }
	    
	    #Make sure we have ids to insert
	    if (count($ids) === 0)
	    {
	        DB::QUERY(NULL, 'COMMIT')->execute();
	        return;
	    }
	    
	    # Build the insert query
	    $insert = DB::insert('forum_groups_forums', array('forum_forum_id', 'forum_group_id'));
	    
	    # Add all the values to the query
	    $values = array();
	    foreach($ids as $id)
	    {
	        if (Validate::digit($id))
	        {
	            $insert->values(array($id, $this->id));
	        }
	        else
	        {
	            DB::QUERY(NULL, 'ROLLBACK');
	            throw new Kohana_Request_Exception('Database error.', NULL, 500);
	        }
	    }
	    
	    # If we got here, they were cleared properly and we have inserts to perform, now execute the query
	    $rows_inserted = $insert->execute();
	    
	    # Index 1 is number of rows
	    if ($rows_inserted[1] === count($ids))
	    {
	        DB::QUERY(NULL, 'COMMIT')->execute();
        }
	    else
	    {
	        DB::QUERY(NULL, 'ROLLBACK')->execute();
	        throw new Kohana_Request_Exception('Database Error.', NULL, 500);
	    }
        
	}
	
	/**
	 * Static function to set the groups a user is a part of
	 * 
	 * @param   int  $user_id  The id of the user
	 * @param   array  $group_ids  An array of group ids that the user is now a part of
	 * @throws  Kohana_Request_Exception
	 * @return  void
	 */
	public static function set_user_groups($user_id, $group_ids)
	{
	    # Start the transaction
	    DB::QUERY(NULL, 'BEGIN')->execute();
	    
	    # How many relationships exists already?
	    $count = DB::select(DB::expr('COUNT(*) AS mycount'))->from('forum_groups_users')->where('user_id', '=', $user_id)->execute()->get('mycount');
	    
	    # Clear the relationships for this user
	    $rows_deleted = DB::delete('forum_groups_users')->where('user_id', '=', $user_id)->execute();
	    
	    # Were there any to delete and if so, were they deleted?
	    if ($count > 0 AND $rows_deleted != $count)
	    {
	        # Not all cleared, roll the transaction
	        DB::QUERY(NULL, 'ROLLBACK')->execute();
	        
	        # Throw a database error.
	        throw new Kohana_Request_Exception('Database error.', NULL, 500);
	    }
	    
	    #Make sure we have ids to insert
	    if (count($group_ids) === 0)
	    {
	        DB::QUERY(NULL, 'COMMIT')->execute();
	        return;
	    }
	    
	    # Build the insert query
	    $insert = DB::insert('forum_groups_users', array('user_id', 'forum_group_id'));
	    
	    # Add all the values to the query
	    $values = array();
	    foreach($group_ids as $id)
	    {
	        if (Validate::digit($id))
	        {
	            $insert->values(array($user_id, $id));
	        }
	        else
	        {
	            DB::QUERY(NULL, 'ROLLBACK');
	            throw new Kohana_Request_Exception('Database error.', NULL, 500);
	        }
	    }
	    
	    # If we got here, they were cleared properly and we have inserts to perform, now execute the query
	    $rows_inserted = $insert->execute();
	    
	    # Index 1 is number of rows
	    if ($rows_inserted[1] === count($group_ids))
	    {
	        DB::QUERY(NULL, 'COMMIT')->execute();
        }
	    else
	    {
	        DB::QUERY(NULL, 'ROLLBACK')->execute();
	        throw new Kohana_Request_Exception('Database Error.', NULL, 500);
	    }
	}
    
    /**
     * Callback to see if a group with a title exists, used as a callback
     * for validation
     *
     * @param   Validation  $array  Validation object
     * @param   String  $field  The name of the field being checked
     * @return  mixed
     */
	public function title_available(Validate $array, $field)
	{
		if ($this->loaded() AND $this->_object[$field] === $array[$field])
		{
			#Title isn't being changed, so don't worry about checking
			return TRUE;
		}
			
		if (ORM::factory('forum_group')->where($field,'=',$array[$field])->find_all(1)->count() )
		{
			$array->error($field, 'title_available');
		}
	}
    
}