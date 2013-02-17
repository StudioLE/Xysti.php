# Xysti on [Laravel](http://laravel.com)

A feature rich content framework bundle for Laravel.

At its simplest Xysti makes generating navigation menus, breadcrumbs and page titling simple by drawing information from a sitemap array. But Xysti is far more powerful than that, it extends the [Laravel](http://laravel.com) framework making development of both static and dynamic sites a breeze.


## Features

- Flexible template helper functions
- Automatically load template and content views
- Automatically validate post data (requires Former)
- Easily generate login/logout pages


## Installation

Installating Xysti only takes a few steps.
First, upload all the files to the bundles directory.
Second, copy the two config files `xysti.php` and `sitemap.php` to your `application/config` directory.
Finally, add xysti to your bundles array: `application/bundles.php`.

```php
return array(

	'xysti' => array(
		'auto' => true,
		'handles' => '(:any)',
		'autoloads' => array(
			'map' => array(
			    'Xysti' => '(:bundle)/xysti.php'
			)
		)
	),

);
```

## Configuration



## Documentation

