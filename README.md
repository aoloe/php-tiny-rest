# Tiny Rest

Set of three tiny classes for creating a tiny rest App.

Provides: 

- An App that let you register Post and Get routes.
- A mandatory `action` argument (no rewrite needed or supported).
- An Http Request class that queries `$_POST` and `$_GET` (with support for Json over Post).
- An Http Response class that returns Json encoded replies.


## Usage

### Download the file and include it

You can simply download the [TinyRest.php](https://raw.githubusercontent.com/aoloe/php-tiny-rest/master/src/TinyRest.php) file, put it somewhere on your disk and include it from your script.

```php
include('TinyRest.php`);
```

### Get the Github repository and load it through Composer

You can get the repository from Github: <https://github.com/aoloe/php-tiny-rest>...

... and then link it in your projects `composer.json`by the path on your computer:

```json
{
	...

    "repositories": [
        {
            "type": "path",
            "url": "/your/path/to/php-tiny-rest"
        }
    ],
    "require": {
        "aoloe/tiny-rest": "@dev"
    }

	...
}
```

See the test script below for a basic usage (and TinyRest cannot do much more than that...).

### Let Composer get TinyRest from Github

You can also tell Composer to get the TinyRest from Github:

```json
{
	...

    "repositories": [
		{
			"type": "vcs",
			"url":  "git@github.com:aoloe/php-tiny-rest.git"
		},
	],
    "require": {
        "aoloe/tiny-rest": "dev-master"
    }
}
```

See the test script below for a basic usage (and TinyRest cannot do much more than that...).

## A test script

```php
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('vendor/autoload.php');

$app = new Aoloe\TinyRest\App();

$request = Aoloe\TinyRest\HttpRequest::create();
$response = new Aoloe\TinyRest\HttpResponse();

$app->get('list', function() use($request, $response) {
    $response->respond(['test' => 'This is a test', 'you' => $request->get('me')]);
});

if (!$app->run($request)) {
    $response->respond($app->error_message);
}
```

If you're using the php internal server, you can test the script with:

```
http://localhost:8080?action=list&me=arthur
```
