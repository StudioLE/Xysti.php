<?php

// 	Xysti Config
// ------------------------------------------------
// Move this file to `application/config`
// ------------------------------------------------

return array(

	/**
	 * Master template view
	 * 
	 * This should be a php file within your `application/views` directory.
	 */
	'template' => 'master',

	/**
	 * Google analytics
	 *
	 * Specify your analytics code for use in the template view.
	 */
	'analytics' => 'UA-XXXXXXX-X',

	/**
	 * Authentication model
	 *
	 * Which authentication method model should we use?
	 * Currently permits 'sentry' or 'default'.
	 * FALSE will disable Xysti's authentication routes.
	 */
	'auth' => 'sentry',

	/**
	 * Authentication routes
	 *
	 * Which uri should Xysti map authentication functions to?
	 * To disable auth routes set `auth` to FALSE
	 */
	'auth_routes' => array(
		'login' => 'login',
		'logout' => 'logout',
		'register' => 'register'
	),

	/**
	 * TimThumb path
	 *
	 * The path at which timthumb ?src urls should be pointed.
	 * I recommend setting renaming timthumb.php to index.php and referencing the directory. 
	 */
	'timthumb' => 'img/timthumb.php',

	/**
	 * Errors
	 */
	'errors' => array(
		'403' => array(
			'title' => 'You\'re not supposed to be here',
			'content' => '<p><b>Seriously. This page is forbidden. No one is supposed to know it exists. Be gone! Be gone! Be gone! Before you\'re discovered!</b></p>',
			'header' => 'HTTP/1.1 403 Forbidden'
		),
		'404' => array(
			'title' => 'Something\'s gone wrong',
			'content' => '<p><b>The page you were looking for could not be found</b></p>',
			'header' => 'HTTP/1.1 404 Not Found'
		),
		'500' => array(
			'title' => 'Something\'s gone wrong',
			'content' => '<p><b>We\'ll get it fixed for you as soon as possible.</b></p>',
			'header' => 'HTTP/1.1 500 Internal Server Error'
		),
		'generic' => array(
			'title' => 'Something\'s gone wrong',
			'content' => '<p><b>We\'ll get it fixed for you as soon as possible.</b></p>'
		)
	),

	/**
	 * Downloads
	 * 
	 * An array of named downloads for use with the downloads() template function.
	 */
	'downloads' => array(
		'conversation-club-workbook' => array(
			'uri' => 'foo/bar.pdf',
			'title' => 'Foo Bar'
		),
		'50mb' => array(
			'uri' => '50mb.dat',
			'title' => '50 MB Random Data'
		)
	)

);