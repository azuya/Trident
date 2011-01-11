<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forum API Base Controller
 * 
 * @package    Forums
 * @author     James Hiscock
 * @copyright  (c) 2011 James Hiscock
 * @license    See LICENSE.md
 */
abstract class Controller_Forum_Api_Core_Base extends Controller {
	
	protected $_config; # Variable to store the forum configuration dictionary
	
	# Override the construct to provide some good error handling;
	public function __construct(Request $request)
	{
		// Assign the request to the controller
		$this->request = $request;
	}
	
	public function before()
	{
		parent::before();
		
		# Is this an internal request? If not, throw a 404, these shouldn't exist to the outside world
		if ($this->request === Request::$instance)
			throw new Kohana_Request_Exception('This page doesn\'t exist', NULL, 404);
		
		# Get a reference to the forum config
		$this->_config = Kohana::config('forum/settings');
		
		# Get a reference to the default route
		$this->_route = Route::get('forums.api.default');
		
		# Get a reference to the default front controller route
		$this->_front = Route::get('forums.front.default');
		
		# Set up the user for the request
		$this->a2 = A2::instance('forum/a2');
		$this->a1 = $this->a2->a1;
		
		$this->user = $this->a2->get_user();
	}
}