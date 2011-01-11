<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Front Forum Thread Controller
 *
 * Front facing Thread Controller for the forums module
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
class Controller_Forum_Front_Thread_Core extends Controller_Forum_Front_Base {
    
    /**
     * @var  boolean  Determines if the controller should automatically render the template or not
     */
    public $auto_render = TRUE;
    
    /**
     * New Action
     *
     * Create a new thread in a forum with the given forum id
     * 
     * @param   String  $forum_id  The id of the forum to create the thread in
     * @return  void
     */
    public function action_new($forum_id)
    {
        # Access the API
        $request = Request::factory(Route::get('forums.api.thread.new')->uri(array(
            'forum_id' => $forum_id
        )))->execute();
        
        # Use the response
        if ($request->status === 303)
            # Action performed successfully, asking for redirect
            $this->request->redirect($this->_front->uri(array(
                'controller' => 'forum',
                'action' => 'view',
                'id' => $forum_id
            )));
        
        # Output the response    
        $this->template->content = (String) $request;
    }
    
    /**
     * View Action
     * 
     * View a thread with a given id
     * 
     * @param   String  $id  The id of the thread to view
     * @return  void
     */
    public function action_view($id)
    {
        # Access the API
        $request = Request::factory($this->_api->uri(array(
            'controller' => 'thread',
            'action' => 'view',
            'id' => $id
        )))->execute();
        
        # Send the response out there
        $this->template->content = (String) $request;
    }
    
    /**
     * Edit Action
     * 
     * Edit a thread with a given id
     * 
     * @param   String  $id  The id of the thread to edit
     * @return  void
     */
    public function action_edit($id)
    {
        # Access the API
        $request = Request::factory($this->_api->uri(array(
            'controller' => 'thread',
            'action' => 'edit',
            'id' => $id
        )))->execute();
        
        # Use the response
        if ($request->status === 303)
            # Action performed successfully, asking for redirect
            $this->request->redirect($this->_front->uri(array(
                'controller' => 'thread',
                'action' => 'view',
                'id' => $id
            )));
        
        # Send the response out there
        $this->template->content = (String) $request;
    }
    
    /**
     * Delete Action
     * 
     * Delete a thread with a given id
     * 
     * @param   String  $id  The id of the thread to delete
     * @return  void
     */
    public function action_delete($id)
    {
        # Access the API
        $request = Request::factory($this->_api->uri(array(
            'controller' => 'thread',
            'action' => 'delete',
            'id' => $id
        )))->execute();
        
        # Use the response
        if ($request->status === 303)
            # Action performed successfully, asking for redirect
            # Use referrer for this one
            $this->request->redirect(Request::$referrer);
        
        # Send the response out there
        $this->template->content = (String) $request;
    }
    
}