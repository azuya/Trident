<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forum Groups Forums Relationship Model
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
class Model_Forum_Core_Relationships_Group_Forum extends ORM {
    
    /**
     * @var  String  Custom table name
     */
    public $_table_name = 'forum_groups_forums';
    
    /**
     * @var  Array  Belongs To relationships list
     */
    protected $_belongs_to = array(
        'group' => array('model' => 'forum_group', 'foreign_key' => 'forum_group_id'),
        'forum' => array('model' => 'forum_forum', 'foreign_key' => 'forum_forum_id'),
    );
    
}