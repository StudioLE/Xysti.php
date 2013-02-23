# Xysti on [Laravel](http://laravel.com)

A feature rich content framework bundle for Laravel.

Xysti makes templating simpler. You provide a sitemap array containing page meta and Xysti's template helper generates your `<title>` tags, breadcrumbs, navigation menus and more.

Xysti's sitemap makes many tasks simpler, for example to require user authentication for a page just include `'auth' => TRUE` in the page meta. Need to hide a page from the navigation menu? `'hidden' => TRUE`

Xysti makes developing both static and dynamic websites a breeze a master template is automatically loaded with your page content nested.


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


### Template Helper

#### nav( $args )
Generate the `<li>` elements for a Bootstrap navigation menu
- `echo` `bool` Echo or return the output. Default is `true`
- `start` `int` Start at level #. Default is `null`
- `depth` `int` Stop at level #. Default is `2`

#### breadcrumbs( $args )
Generate Bootstrap styled breadcrumbs
- `echo` `bool` Echo or return the output. Default is `true`

#### head_title( $args )
Generate `<title>`
- `echo` `bool` Echo or return the output. Default is `true`
- `home` `string` String to overwrite home title. Default is `null`
- `sep` `string` Separator. Default is ` &rsaquo; `

#### page_title( $args )
Generate `<h1>`, `<h2>` etc
- `echo` `bool` Echo or return the output. Default is `true`
- `tag` `string` Heading tag. Default is `h1`
- `caption` `string` Subtitle inside `<h>`. Default is `Xysti::page('caption')`
- `a` `bool` Include `<a>` tags. Default is `false`
- `href` `string` href of  `<a>`. Default is `URI::current()`
- `title` `string` The heading title. Default is `Xysti::page('title')`

#### button( $args )
Generate a Bootstrap styled button
- `echo` `bool` Echo or return the output. Default is `true`
- `class` `string` Additional classes. Default is `null`
- `tag` `string` `<a>`, `<input type="submit"` or `<input type="button"`. Default is `a`
- `value` `string` Button value. Default is ``
- `href` `string` Button link. Default is `#`
- `target` `string` Link target. Default is `false`
- `icon` `string` Bootstrap / Font Awesome icon. Default is `download-alt`
- `after` `string` Output after. Default is ``

#### downloads( $download_keys )
Generate Bootstrap styled thumbnails
- An array of Xysti download keys
