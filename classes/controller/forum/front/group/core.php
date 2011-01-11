<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forum Front Group Controller
 * 
 * Front Facing Group Controller for the Forums module.
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
class Controller_Forum_Front_Group_Core extends Controller_Forum_Front_Base {
    
    /**
     * @var  boolean  Determines if the controller should automatically render the template or not
     */
    public $auto_render = TRUE;
    
    /**
     * Group Index Action
     *
     * A list of all forum groups, with a few global group actions
     * 
     * @return  void
     */
    public function action_index()
    {
        # Access the API
        $request = Request::factory($this->_api->uri(array(
            'controller' => 'group',
            'action' => 'index',
        )))->execute();
        
        # Output the request
        $this->template->content = (String) $request;
    }
    
    /**
     * New Group Action
     *
     * Create a new group
     *
     * @return  void
     */
    public function action_new()
    {
        # Access the API
        $request = Request::factory($this->_api->uri(array(
            'controller' => 'group',
            'action' => 'new',
        )))->execute();
        
        # Use the response
        if ($request->status === 303)
            # Action performed successfully, asking for redirect
            $this->request->redirect($this->_front->uri(array(
                'controller' => 'group',
                'action' => 'index',
            )));
        
        # Output the response    
        $this->template->content = (String) $request;
    }
    
    /**
     * Edit Group Action
     *
     * Edit an existing group identified by id
     *
     * @param   String  $id  The id of the group to edit
     * @return  void
     */
    public function action_edit($id)
    {
        # Access the API
        $request = Request::factory($this->_api->uri(array(
            'controller' => 'group',
            'action' => 'edit',
            'id' => $id
        )))->execute();
        
        # Do we need a redirect?
        if ($request->status === 303)
            # Action performed successfully, asking for a redirect
            $this->request->redirect($this->_front->uri(array(
                'controller' => 'group',
                'action' => 'index',
            )));
        
        # Use the response
        $this->template->content = (string) $request;
    }
    
    /**
     * Delete Group Action
     *
     * Delete an existing group identified by id
     *
     * @param   String  $id  The id of the group to delete
     * @return  void
     */
    public function action_delete($id)
    {
        # Access the API
        $request = Request::factory($this->_api->uri(array(
            'controller' => 'group',
            'action' => 'delete',
            'id' => $id
        )))->execute();
        
        # Do we need a redirect?
        if ($request->status = 303)
            # Action performed successfully, asking for a redirect
            $this->request->redirect(Request::$referrer);
        
        # Use the response
        $this->template->content = (String) $request;
    }
    
    /**
     * Forums Group Action
     *
     * Manage the forums that a particular group had access to
     *
     * @param   String  $id  The id of the group to manage forums for
     * @return  void
     */
    public function action_forums($id)
    {
        # Access the API
        $request = Request::factory($this->_api->uri(array(
            'controller' => 'group',
            'action' => 'forums',
            'id' => $id,
        )))->execute();
        
        $this->template->content = (String) $request;
    }
    
    /**
     * User Groups Action
     *
     * Manage the groups a user is a member of
     *
     * @param   String  $user_id  The id of the user to manage groups for
     * @return  void
     */
    public function action_user($user_id)
    {
        # Access the API
        $request = Request::factory($this->_api->uri(array(
            'controller' => 'group',
            'action' => 'user',
            'user_id' => $user_id,
        )))->execute();
        
        $this->template->content = (String) $request;
    }
}