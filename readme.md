Frenzy/Localize
===============

This [stack middleware](http://stackphp.com) gives localization features to your
application. It will start looking for the locale in the URL. If none is
present, it will default to the one you provide.

No default will result in getting the HTTP_ACCEPT_LANGUAGE from the user and
try to match it to an available locale.

This middleware can detect language specified on a URI prefix. ex.:
```
http://mydomain.com/en
http://mydomain.com/fr
```

The locale will be accessible on the Request getLocale() method and be removed
from the request pathinfo for easier routing.

#### Example with Silex
```php
<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

// Accessible on /{locale}/
$app->get('/', function (Request $request) {
	// The locale should be set.
	return $request->getLocale();
});

// Accessible on /{locale}/test
$app->get('/test', function (Request $request) {
    // The locale should be set.
	return $request->getLocale().'/test';
});

$locales = ['en', 'fr', 'es'];
$fallback = 'en';
$redirectDefault = true;

$stack = new Stack\Builder();
$stack->push('Frenzy\Localize\StackLocalize', $locales, $fallback, $redirectDefault);
$app = $stack->resolve($app);

$request  = Request::createFromGlobals();
$response = $app->handle($request)->send();

$app->terminate($request, $response);
```

#### Example with Laravel
```php
<?php

$params = [
    'locales' => ['fr', 'en', 'es'],
    'fallback' => 'fr',
    'redirectDefault' => true,
];

App::middleware('Frenzy\Localize\StackLocalize', $params);

// Be sure to set the locale from the request when booting your app.
App::setLocale(Request::getLocale());

```

#### TODO
 - Tests
