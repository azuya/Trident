<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forum Groups Forums Relationship Model
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
class Model_Forum_Core_Relationships_Group_Category extends ORM {
    
    /**
     * @var  String  Custom table name
     */
    protected $_table_name = 'forum_groups_categories';
    
    /**
     * @var  Array  Belongs To relationships list
     */
    protected $_belongs_to = array(
        'group' => array('model' => 'forum_group', 'foreign_key' => 'group_id'),
        'category' => array('model' => 'forum_category', 'foreign_key' => 'category_id'),
    );
    
}