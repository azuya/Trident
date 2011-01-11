<?php defined('SYSPATH') or die('No direct script access.');

/**
 * The Front Forum Controller
 *
 * The front-facing Forum controller for the Forums module
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
class Controller_Forum_Front_Forum_Core extends Controller_Forum_Front_Base {
    
    /**
     * @var  boolean  Determines if the controller should automatically render the template or not
     */
    public $auto_render = TRUE;
    
    /**
     * Index Action
     *
     * Lists all the categories and their forums that are visible to the user
     *
     * @return  void
     */
    public function action_index()
    {
        $request = Request::factory(Route::get('forums.api.index')->uri());
        $this->template->content = $request->execute();
    }
    
    /**
     * View Action
     * 
     * View a forum
     *
     * @param   String  $id  The id of the forum to view
     * @return  void
     */
    public function action_view($id)
    {
        # Access the API
        $request = Request::factory($this->_api->uri(array(
            'controller' => 'forum',
            'action' => 'view',
            'id' => $id
        )));
        
        # Send the response out there
        $this->template->content = $request->execute();
    }
    
    /**
     * New Action
     *
     * Form to create a new forum
     * 
     * @param   String  $category_id  The id of the parent category
     * @return  void
     */
    public function action_new($category_id)
    {
        # Access the API
        $request = Request::factory(Route::get('forums.api.forum.new')->uri(array(
            'category_id' => $category_id
        )))->execute();
        
        # Use the response
        if ($request->status === 303)
            # Action performed successfully, asking for redirect
            $this->request->redirect(Route::get('forums.front.index')->uri());
        
        # Output the response    
        $this->template->content = (String) $request;
    }
    
    /**
     * Edit Action
     * 
     * Form to edit an existing forum
     * 
     * @param   String  $id  The id of the forum to edit
     * @return  void
     */
    public function action_edit($id)
    {
        # Access the API
        $request = Request::factory($this->_api->uri(array(
            'controller' => 'forum',
            'action' => 'edit',
            'id' => $id
        )))->execute();
        
        # Do we need a redirect?
        if ($request->status === 303)
            # Action performed successfully, asking for a redirect
            $this->request->redirect($this->_front->uri(array(
                'controller' => 'forum',
                'action' => 'view',
                'id' => $id
            )));
        
        # Use the response
        $this->template->content = (String) $request;
    }
    
    /**
     * Delete Action
     * 
     * Delete an existing form
     * 
     * @param   String  $id  The id of the forum to delete
     * @return  void
     */
    public function action_delete($id)
    {
        # Access the API
        $request = Request::factory($this->_api->uri(array(
            'controller' => 'forum',
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
    
}