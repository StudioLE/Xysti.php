<?php

// 	Xysti Config
// ------------------------------------------------
// Move this file to `application/config`
// ------------------------------------------------

return array(

	/**
	 * Master template view
	 */
	'template' => 'master',

	/**
	 * Use less
	 */
	'less' => 0,

	/**
	 * Google analytics
	 */
	'analytics' => 'UA-XXXXXXX-X',

	/**
	 * TimThumb path
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