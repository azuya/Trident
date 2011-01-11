<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forum Front Category Controller
 *
 * The front facing controller for Forum Categories
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
class Controller_Forum_Front_Category_Core extends Controller_Forum_Front_Base {
    
    /**
     * @var  boolean  Determines if the controller should automatically render the template or not
     */
    public $auto_render = TRUE;
    
    /**
     * New Action
     * 
     * Create a new category
     *
     * @return  void
     */
    public function action_new()
    {
        # Access the API
        $request = Request::factory($this->_api->uri(array(
            'controller' => 'category',
            'action' => 'new',
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
     * Edit a category with a given id
     *
     * @param   String  $id  The id of the category to edit
     * @return  void
     */
    public function action_edit($id)
    {
        # Access the API
        $request = Request::factory($this->_api->uri(array(
            'controller' => 'category',
            'action' => 'edit',
            'id' => $id
        )))->execute();
        
        # Use the response
        if ($request->status === 303)
            # Action performed successfully, asking for redirect
            $this->request->redirect(Route::get('forums.front.index')->uri());
        
        # Output the response    
        $this->template->content = (String) $request;
    }
    
    /**
     * Delete Action
     * 
     * Delete a category with a given id
     * 
     * @param   String  $id  The id of the category to delete
     * @return  void
     */
    public function action_delete($id)
    {
        # Access the API
        $request = Request::factory($this->_api->uri(array(
            'controller' => 'category',
            'action' => 'delete',
            'id' => $id
        )))->execute();
        
        # Use the response
        if ($request->status === 303)
            # Action performed successfully, asking for redirect
            $this->request->redirect(Route::get('forums.front.index')->uri());
        
        # Output the response    
        $this->template->content = (String) $request;
    }
}