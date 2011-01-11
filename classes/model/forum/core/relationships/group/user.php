<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forum Groups Users Relationship Model
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
class Model_Forum_Core_Relationships_Group_User extends ORM {
    
    /**
     * @var  String  Custom table name
     */
    protected $_table_name = 'forum_groups_users';
    
    /**
     * @var  Array  Belongs To relationships list
     */
    protected $_belongs_to = array(
        'group' => array('model' => 'forum_group', 'foreign_key' => 'forum_group_id'),
        'user' => array('model' => 'user', 'foreign_key' => 'user_id'),
    );
    
}