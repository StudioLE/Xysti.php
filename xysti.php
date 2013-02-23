<?php

//	Xysti
//	Developed by Laurence Elsdon
//	elsdon.me
//	@iSpyCreativity
//	---------------------------------------------------
//	Changelog
//	2013-02-23 Beta Release
//	---------------------------------------------------


/**
 * Xysti
 * 
 * A Laravel bundle adding a multitude of features.
 * http://elsdon.me
 * @version 1.0
 */
class Xysti {


	/**
	 * Sitemap array
	 * 
	 * The sitemap as collected and extended config
	 * @var array
	 */
	private static $sitemap;


	/**
	 * Cached page attributes
	 * @var array
	 */
	private static $page;


	/**
	 * Template / Content view strings
	 * @var string
	 */
	private static $views = array();


	/**
	 * Render a string instead of a content view
	 * @var string
	 */
	private static $content;


	/**
	 * Cached URI array
	 * @var array
	 */
	private static $uri_array;


	/**
	 * Helpers already included
	 * @var array
	 */
	private static $helpers = array();


	/**
	 * Data for use in views
	 * @var array
	 */
	public static $data;


	// 	Class assets
	// ------------------------------------------------


	/**
	 * Version number
	 * 
	 * Check current page number
	 * @return string
	 */
	public static function version()
	{
		return '1.0';
	}


	/**
	 * Include helper files
	 * 
	 * Plays a couple of tricks to help integrate
	 * CodeIgniter helper files
	 * @param string $helper The helper name excluding '_helper.php'
	 */
	public static function helper($helper)
	{
		// Only include if it's not been included before
		if(in_array($helper, Xysti::$helpers)):
			return TRUE;
		else:
			Xysti::$helpers[] = $helper;
		endif;

		// Quick hack to use codeigniter helpers easier
		if ( ! defined('BASEPATH')) {
			define('BASEPATH', URL::base());
		}
		include 'bundles/xysti/helpers/' . $helper . '_helper.php';
	} // helper()

	
	/**
	 * Variable debug
	 * @param mixed $var
	 * @param bool $collapse
	 */
	public static function dbug($var, $collapse = FALSE)
	{
		if( ! in_array('dbug', Xysti::$helpers)) {
			Xysti::helper('dbug');
		}

		if($collapse):
			new dbug($var, '', FALSE);
		else:
			new dbug($var);
		endif;
	}



	// 	Request controllers
	// ------------------------------------------------


	/**
	 * Checks for auth / redirect
	 * 
	 * Checks wether the page needs authorisation or 
	 * is a redirect before 
	 * @return string
	 */
	public static function before()
	{

		// Is auth required
		if(Xysti::page('auth')):
			$auth_driver = Config::get('xysti.auth', 'default');
			// Default auth
			if($auth_driver == 'default'):
				$auth = Auth::check();
			// Sentry auth
			elseif($auth_driver == 'sentry'):
				$auth = Sentry::check();
			else:
				return Xysti::error(500, 'Unknown authentication driver.');
			endif;
			if( ! $auth) {
				return Redirect::to('login', 403)->with('warning', 'You must be signed in to do that')->with('success_redirect', URI::current());
			}
		endif;

		// Is this a redirect
		if(Xysti::page('redirect')) {
			return Redirect::to(Xysti::page('redirect'), 301);
		}
	}


	/**
	 * Perform input validation
	 * 
	 * Checks wether a page variable is set in the sitemap and returns it
	 * @return string
	 */
	public static function validate()
	{
		$rules = Xysti::page('post_rules');

		if(is_array($rules)):
			$validation = Validator::make(Input::all(), $rules);
		else:
			return Xysti::error(500, 'Expecting post rules array.');
		endif;

		// If validation has failed
		if($validation->fails()):
		//	new dbug($validation);
		//	new dbug(Former);
			Session::flash('warning', 'Could not submit. Validation errors were found.');
			Former::withErrors($validation);
			// Make the page without any more routes
			return Xysti::make();
			// @todo some mechanism to redirect on failure if that's prefered.
			//return Redirect::to(URI::current())->with_errors($validation);
		endif;
	}


	/**
	 * Render a view
	 * 
	 * Picks the template and the page content
	 * @return string
	 */
	public static function make()
	{
		
		// Which template?

		if(Xysti::page('template') == 'none'):
			Xysti::$views['template'] = FALSE;
			if(Xysti::page('content')):
				Xysti::$views['template'] = Xysti::page('content');
			else:
				Xysti::$views['template'] = 'content.' . URI::current();
			endif;
		elseif(Xysti::page('template')):
			Xysti::$views['template'] = Xysti::page('template');
		else:
			Xysti::$views['template'] = Config::get('xysti.template');
		endif;

		// What content?

		// Content must have a title in the sitemap
		if(Xysti::page('title') != 'Error'):
			// Is content explicitly set?
			if(Xysti::page('content')):
				Xysti::$views['content'] = 'content.' . Xysti::page('content');
			// Is this an incorrecty configured dynamic page?
			elseif(Xysti::page('/') == 'dynamic'):
				Xysti::error(500, 'Dynamic pages must have a content variable specified.');
			// Else use the URI
			else:
				Xysti::$views['content'] = 'content.' . str_replace('/', '.', URI::current());
			endif;
		else:
			Log::write('info', 'No sitemap entry for ' . URI::current() . '.');
		endif;

		// Time to return a view!

		// Is the content set?
		if(isset(Xysti::$views['content']) OR isset(Xysti::$content)):
			// If there is a template then load it
			if(Xysti::$views['template']):
				return View::make(Xysti::$views['template']);
			// Else just load the content
			else:
				return View::make(Xysti::$views['content']);
			endif;
		// Else 404
		else:
			return Xysti::error(404);
		endif;
	}




	// 	View functions
	// ------------------------------------------------


	/**
	 * Error controller
	 * 
	 * Render a view for an error code
	 * @param int $file Optional HTTP status
	 */
	public static function error($error_code, $reason = NULL)
	{
		$errors = Config::get('xysti.errors');

		if(isset($errors[$error_code])):
			$error = $errors[$error_code];
			$error['code'] = $error_code;
		else:
			$error = $errors['generic'];
			$error['code'] = 'Generic';
		endif;

		Log::write('error', 'Error ' . $error['code'] . ' at ' . URI::current() . '. ' . $reason);

		Xysti::$views['content'] = 'content.misc.error';

		Xysti::$data['error'] = $error;

		$view = View::make(Config::get('xysti.template'));

		if($error['code'] == 'Generic'):
			return $view;
		else: 
			return Response::make($view, $error['code']);
		endif;
	}


	/**
	 * Content render
	 * 
	 * Renders the content view or content string 
	 * @param array $args
	 * @return string
	 */
	public static function content($args = array()) {
		$args = array_merge(array(
			// Set defaults here
			'echo' => TRUE,
			'view' => ''
		), $args);

		if(empty(Xysti::$content)):
			if( ! empty($args['view'])):
				$output = render($args['view']);
			else:
				$output = render(Xysti::$views['content']);
			endif;
		else:
			$output = Xysti::$content;
		endif;
		
		if($args['echo']):
			echo $output;
		else:
			return $output;
		endif;
	}


	/**
	 * Partials render
	 * 
	 * Checks what to load then loads it or an error page
	 * @return bool
	 */
	public static function partial()
	{
		
	}



	// 	Data models 
	// ------------------------------------------------


	/**
	 * Return the sitemap
	 * 
	 * Checks whether the sitemap has been fetched, then returns it.
	 * @return array Xysti::$sitemap
	 */
	public static function sitemap()
	{
		if( ! is_array(Xysti::$sitemap)) {
			Xysti::$sitemap = Config::get('sitemap');
		}
		return Xysti::$sitemap;
	}


	/**
	 * Extend the sitemap
	 * 
	 * Checks whether the sitemap has been fetched, then returns it.
	 * @param array $extension
	 * @return array Xysti::$sitemap
	 */
	public static function extend_sitemap($extension)
	{
		Xysti::$sitemap = array_merge_recursive(Xysti::sitemap(), $extension);
		return Xysti::$sitemap;
	}


	private static function sitemap_page($segment_count, $segments)
	{
		
		$walk = Xysti::sitemap();

		// Hack to permit numeric URI segments..
		// Must be prefixed in sitemap with _
		foreach($segments as $key => $value) {
			if(intval($value)){ 
				$segments[$key] = '_' . $value;
			}
		}

		// Traverse the sitemap up to the $segment_count
		for($depth = 1; $depth <= $segment_count; $depth++):
			
			$this_segment = $segments[$depth - 1];

			// If we have reached the $segment_count
			if($depth == $segment_count):
				// Return item or 
				if(isset($walk[$this_segment])) {
					return $walk[$this_segment];
				}
				break;
			// If there are still children
			elseif(isset($walk[$this_segment]['/'])):
				// If ['/'] is an array keep traversing
				if(is_array($walk[$this_segment]['/'])):
					$walk = $walk[$this_segment]['/'];
				// If ['/'] == dynamic then all children equal this segment
				elseif($walk[$this_segment]['/'] == 'dynamic'):
					return $walk[$this_segment];
					break;
				// ['/'] is set but has clearly been done so incorrectly so end the loop
				else:
					break;
				endif;
			// If no children then break the loop
			else:
				break;
			endif;
		endfor;

		return FALSE;
	}


	/**
	 * Page variable
	 * 
	 * Checks wether a page variable is set in the sitemap and returns it
	 * @param string $request The variable key to return
	 * @param int $uri_segment Optional segment # of page
	 * @return mixed
	 */
	public static function page($request, $uri = NULL)
	{

		// Use current page if no second argument
		if(is_null($uri)):

			// Check for cached page
			if(is_null(Xysti::$page)) {
				Xysti::$page = Xysti::sitemap_page(Xysti::uri_count(), Xysti::uri_array());
			}
			$page = Xysti::$page;
		// Segment number specified
		elseif(is_int($uri)):
			$page = Xysti::sitemap_page($uri, Xysti::uri_array());
		// Segment string specified
		elseif(is_string($uri)):
			$page = Xysti::sitemap_page(Xysti::uri_count($uri), Xysti::uri_array($uri));
		else:
			Log::write('error', 'Unexpected Xysti::page() call at ' . URI::current() . '.');
		endif;

		Xysti::helper('dbug');

		// Page was found
		if($page):
			// If all are sought
			if($request == 'all'):
				return $page;
			// If it's set return it.
			elseif(isset($page[$request])):
				return $page[$request];
			endif;
		else:
			Log::write('error', 'Children expected but not found in Xysti::page() at ' . URI::current() . '.');
		endif;

		// Either page was not found or attributes not set
		if($request == 'title'):
			return 'Error';
		else:
			return FALSE;
		endif;
	}


	/**
	 * User variable
	 * 
	 * Checks wether a page variable is set in the sitemap and returns it
	 * @param string $request The variable key to return
	 * @param int $uri_segment Optional segment # of page
	 * @return mixed
	 */
	public static function user($request = NULL, $user = NULL)
	{
		if(is_null($request)):
			$request = 'id';
		elseif($request == 'full_name'):
			$full_name = TRUE;
			$request = 'metadata';
		endif;

		try {
			if(is_null($user)):
				$output = Sentry::user()->get($request);
			else:
				$output = Sentry::user($user)->get($request);
			endif;
		}
		catch(Sentry\SentryException $e) {
			Log::write('info', $e->getMessage());
			return FALSE;
		}

		if( ! empty($full_name)):
			return $output['first_name'] . ' ' . $output['last_name'];
		else:
			return $output;
		endif;
	}



	// 	Framework extensions 
	// ------------------------------------------------


	/**
	 * URI to Array
	 * @var string $uri
	 * @return string
	 */
	public static function uri_array($uri = NULL)
	{
		if( ! is_null(Xysti::$uri_array)):
			return Xysti::$uri_array;
		elseif(is_null($uri)):
			$uri = URI::current();
		endif;
		return explode('/', $uri);
	}

	/**
	 * Count URI segments
	 * @var string $uri
	 * @return int
	 */
	public static function uri_count($uri = NULL)
	{
		if(is_null($uri)) {
			$uri = URI::current();
		}
		return count(Xysti::uri_array($uri));
	}

}