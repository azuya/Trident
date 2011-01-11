<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Forum_Front_Base_Core extends Controller_Template {
    
    public $template = 'forums/front/templates/site';
	public $auto_render = false;
	
	protected $_config;
	protected $_api;
	protected $_front;
	
	public function before()
	{
	    parent::before();
	    
	    # Get a reference to the forum config
		$this->_config = Kohana::config('forum/settings');
		
		# Get a reference to the API Routes
		$this->_api = Route::get('forums.api.default');
		
		# Get a reference to the Front Routes
		$this->_front = Route::get('forums.front.default');
		
		
		# Set up the user for the request
		$this->a2 = A2::instance('forum/a2');
		$this->a1 = $this->a2->a1;
		
		$this->user = $this->a2->get_user();
		
		if ($this->auto_render)
		{
		    $this->template->content = '';
		}
	}
}