# Xysti on [Laravel](http://laravel.com)

A feature rich content framework bundle for Laravel.

Xysti makes templating simpler. You provide a sitemap array containing page meta and Xysti's template helper generates your `<title>` tags, breadcrumbs, navigation menus and more.

Xysti's sitemap makes many tasks simpler, for example to require user authentication for a page just include `'auth' => TRUE` in the page meta. Need to hide a page from the navigation menu? `'hidden' => TRUE`

Xysti makes developing both static and dynamic websites a breeze a master template is automatically loaded with your page content nested.


## Features

- Flexible template helper functions
- Master template and content views
- Automated post data validation (requires Former)
- Easily generate login / logout pages
- Rich error views using your master template


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
- `$args['echo']` `bool` Echo or return the output. Default is `true`
- `$args['start']` `int` Start at level #. Default is `null`
- `$args['depth']` `int` Stop at level #. Default is `2`

#### breadcrumbs( $args )
Generate Bootstrap styled breadcrumbs
- `$args['echo']` `bool` Echo or return the output. Default is `true`

#### head_title( $args )
Generate `<title>`
- `$args['echo']` `bool` Echo or return the output. Default is `true`
- `$args['home']` `string` String to overwrite home title. Default is `null`
- `$args['sep']` `string` Separator. Default is ` &rsaquo; `

#### page_title( $args )
Generate `<h1>`, `<h2>` etc
- `$args['echo']` `bool` Echo or return the output. Default is `true`
- `$args['tag']` `string` Heading tag. Default is `h1`
- `$args['caption']` `string` Subtitle inside `<h>`. Default is `Xysti::page('caption')`
- `$args['a']` `bool` Include `<a>` tags. Default is `false`
- `$args['href']` `string` href of  `<a>`. Default is `URI::current()`
- `$args['title']` `string` The heading title. Default is `Xysti::page('title')`

#### button( $args )
Generate a Bootstrap styled button
- `$args['echo']` `bool` Echo or return the output. Default is `true`
- `$args['class']` `string` Additional classes. Default is `null`
- `$args['tag']` `string` `<a>`, `<input type="submit"` or `<input type="button"`. Default is `a`
- `$args['value']` `string` Button value. Default is ``
- `$args['href']` `string` Button link. Default is `#`
- `$args['target']` `string` Link target. Default is `false`
- `$args['icon']` `string` Bootstrap / Font Awesome icon. Default is `download-alt`
- `$args['after']` `string` Output after. Default is ``

#### downloads( $download_keys )
Generate Bootstrap styled buttons for Xysti downloads
- `$download_keys` `array` An array of Xysti download keys

#### thumbnails( $args, $imgs )
Generate Bootstrap styled thumbnails
- `$args['echo']` `bool` Echo or return the output. Default is `true`
- `$args['ul']` `bool` Wrap in `<ul>`. Default is `true`
- `$args['span']` `int` Thumbnail width. Default is `3`
- `$args['lightbox']` `bool` rel="lightbox". Default is `true`
- `$args['tooltip']` `bool` rel="tooltip". Default is `true`
- `$args['timthumb']` `bool` Run timthumb on each img. Default is `false`
- `$args['imgs']` `array` Array of images (merged with $imgs). Default is `array()`
- `$imgs[]['href']` `string` Thumbnail link. Default is `$img['src']`
- `$imgs[]['span']` `int` Thumbnail width. Default is `$args['span']`
- `$imgs[]['title']` `string` Title attribute. Default is `false`
- `$imgs[]['full']` `string` data-full attribute. Default is `false`

#### timbthumb( $args )
Generate a TimThumb link
- `$args['w'] `int` Width. Default is `false`
- `$args['h'] `int` Width. Default is `false`
- `$args['span'] `int` Override width with Bootstrap span. Default is `false`
- `$args['src'] `string` Img src relative to timthumb. Default is ``
- `$args['wh'] `int` Set width or height for lightbox. Default is `false`

#### alerts( $args )
Generate Bootstrap style alerts from session data
- `$args['echo']` `bool` Echo or return the output. Default is `true`
- `$args['dismiss']` `bool` Allow dismiss. Default is `true`
