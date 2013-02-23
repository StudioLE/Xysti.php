<?php

//	Xysti Routes
//	Developed by Laurence Elsdon
//	elsdon.me
//	@iSpyCreativity
//	---------------------------------------------------
//	Changelog
//	2013-02-23 Beta Release
//	---------------------------------------------------


// 	Filters
// ------------------------------------------------


/**
 * Check whether redirect or auth required.
 * This is called before every route automatically.
 */
Route::filter('before', function()
{
	return Xysti::before();
});


/**
 * Validate input
 */
Route::filter('validate', function()
{
	return Xysti::validate();
});



// 	Temporary routes
// ------------------------------------------------


/**
 * Redirect the homepage
 */
Route::get('delete-user/(:num)', function()
{
	try {
		// update the user
		$user = Sentry::user(intval(URI::segment(2)));
		$delete = $user->delete();
		if ($delete):
			Session::flash('success', 'User deleted');
		else:
			Session::flash('warning', 'User not deleted');
		endif;
	}
	catch(Sentry\SentryException $e) {
		Session::flash('error', $e->getMessage());
	}

	Xysti::helper('template');
	alerts();
});



// 	Permanent routes
// ------------------------------------------------


/**
 * Redirect the homepage
 */
Route::get('/', function()
{
	return Redirect::to('home');
});


/**
 * Error requests
 */
Route::get('error/(:num)', function()
{
	return Xysti::error(URI::segment(2));
});


/**
 * Sign the user out and redirect
 */
Route::get('logout', function()
{
	$auth_driver = Config::get('xysti.auth', 'default');

	// Default auth
	if($auth_driver == 'default'):
		Auth::logout();
	// Sentry auth
	elseif($auth_driver == 'sentry'):
		Sentry::logout();
	endif;

	return Redirect::to('login')->with('info', 'You have been signed out');
});


/**
 * Handle login
 */
Route::post('login', array('before' => 'check|validate', function()
{
	$auth_driver = Config::get('xysti.auth', 'default');

	// Default auth
	if($auth_driver == 'default'):
		
		$login = Auth::attempt(array(
			'username' => Input::get('email'),
			'password' => Input::get('password')
		));

	// Sentry auth
	elseif($auth_driver == 'sentry'):

		try {
			$login = Sentry::login(
				Input::get('email'),
				Input::get('password'),
				FALSE
			);
		}
		catch(Sentry\SentryException $e) {
			Session::flash('error', $e->getMessage());
			$login = FALSE;
		}

	else:
		return Xysti::error(500, 'Unknown authentication driver.');
	endif;


	// Login was a success
	if($login):
		if(Session::get('success_redirect')):
			return Redirect::to(Session::get('success_redirect'));
		else:
			return Redirect::to(Xysti::page('post_login'));
		endif;
	// Login failed..
	else:
		Session::flash('warning', 'User and password do not match');
	endif;

	return Xysti::make();
}));


/**
 * Handle registration attempts
 */
Route::post('register', array('before' => 'check|validate', function()
{
	Xysti::helper('dbug');

	$auth_driver = Config::get('xysti.auth', 'default');

	// Default auth
	if($auth_driver == 'default'):
		
		Xysti::error(500, 'Default auth currently not configured for registration.');

	// Sentry auth
	elseif($auth_driver == 'sentry'):

		try {
			
			$user = Sentry::user()->create(array(
				'email' => Input::get('email'),
				'password' => Input::get('password'),
				'metadata' => array(
					'first_name' => Input::get('first_name'),
					'last_name'  => Input::get('last_name'),
				)
			));

			if($user):
				$registration = TRUE;
			else:
				$registration = FALSE;
			endif;
		}
		catch(Sentry\SentryException $e) {
			$errors = $e->getMessage();
			Session::flash('error', $e->getMessage());
			$registration = FALSE;
		}

	else:
		return Xysti::error(500, 'Unknown authentication driver.');
	endif;


	// Registration was a success
	if($registration):

		try {
			Sentry::force_login($user);
		}
		catch(Sentry\SentryException $e) {
			Session::flash('error', $e->getMessage());
		}

		// User activation email..
		if(0):
			$postmark = new Postmark();
			$postmark->to();
			$postmark->subject('Chim chim on the loose again');
			$postmark->txt_body('Hey Speed, Please keep Spritle and Chim chim in line. Love, Racer X.');
			$response = $postmark->send();
		endif;

		if(Session::get('success_redirect')):
			return Redirect::to(Session::get('success_redirect'));
		else:
			return Redirect::to('edit');
		endif;
	// Registration failed..
	else:
		Session::flash('warning', 'Registration failed');
	endif;

	return Xysti::make();
}));


/**
 * Activate a new user and log them in
 */
Route::get('activate/(:any)/(:any)', function()
{
	Xysti::helper('dbug');

	$auth_driver = Config::get('xysti.auth', 'default');

	// Default auth
	if($auth_driver == 'default'):
		
		Xysti::error(500, 'Default auth currently not configured for activation.');

	// Sentry auth
	elseif($auth_driver == 'sentry'):

		try {
			$activate_user = Sentry::activate_user(
				URI::segment(2),
				URI::segment(3),
				FALSE
			);
		}
		catch (Sentry\SentryException $e) {
			// issue activating the user
			// store/set and display caught exceptions such as a suspended user with limit attempts feature.
			$errors = $e->getMessage();
		}
	else:
		return Xysti::error(500, 'Unknown authentication driver.');
	endif;


	if($activate_user):
		//Sentry::force_login(URI::segment(2));
		return Redirect::to(Xysti::page('login', 'post_login'));
	else:
		return Xysti::make(500, 'User activation failed.');
	endif;

});


// 	Catch all routes
// ------------------------------------------------


/**
 * Handle remaining GET requests
 */
Route::get('(.*)', function()
{
	return Xysti::make();
});


/**
 * Handle remaining POST requests
 */
Route::post('(.*)', function()
{
	return Xysti::error(500, 'No post route.');
});
