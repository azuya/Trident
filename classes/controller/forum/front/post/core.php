<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Front Forum Post Controller
 * 
 * Front facing Post Controller for the Forums module
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
class Controller_Forum_Front_Post_Core extends Controller_Forum_Front_Base {
    
    /**
     * @var  boolean  Determines if the controller should automatically render the template or not
     */
    public $auto_render = TRUE;
    
    /**
     * New Post Action
     *
     * Create a new post as a reply to a thread given by a thread id
     *
     * @param   String  $thread_id  The id of the thread to make the post in
     * @return  void
     */
    public function action_new($thread_id)
    {
        # Access the API
        $request = Request::factory(Route::get('forums.api.post.new')->uri(array(
            'thread_id' => $thread_id
        )))->execute();
        
        # Use the response
        if ($request->status === 303)
            # Action performed successfully, asking for redirect
            $this->request->redirect($this->_front->uri(array(
                'controller' => 'thread',
                'action' => 'view',
                'id' => $thread_id
            )).'?page=last');
        
        # Output the response    
        $this->template->content = (String) $request;
    }
    
    /**
     * Edit Post Action
     * 
     * Edit an existing post identified by id
     * 
     * @param   String  $id  The id of the post to edit
     * @return  void
     */
    public function action_edit($id)
    {
        # Access the API
        $request = Request::factory($this->_api->uri(array(
            'controller' => 'post',
            'action' => 'edit',
            'id' => $id
        )))->execute();
        
        # We need the thread id
        $thread_id = ORM::factory('forum_post', $id)->thread_id;
        
        # Use the response
        if ($request->status === 303)
            # Action performed successfully, asking for redirect
            $this->request->redirect($this->_front->uri(array(
                'controller' => 'thread',
                'action' => 'view',
                'id' => $thread_id
            )).'?page=last');
        
        # Send the response out there
        $this->template->content = (String) $request;
    }
    
    /**
     * Delete Post Action
     * 
     * Delete an existing post identified by id
     * 
     * @param   String  $id  The id of the post to delete
     * @return  void
     */
    public function action_delete($id)
    {
        # Access the API
        $request = Request::factory($this->_api->uri(array(
            'controller' => 'post',
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