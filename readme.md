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

use Frenzy\Localize\Request;

$app = new Silex\Application();

$app->get('/', function (Request $request) {
	return $request->getLocale();
});

$locales = ['en', 'fr', 'es'];
$fallback = 'en';

$stack = new Stack\Builder();
$stack->push('Frenzy\Localize\StackLocalize', $locales, $fallback);
$app = $stack->resolve($app);

$request  = Request::createFromGlobals();
$response = $app->handle($request)->send();

$app->terminate($request, $response);
```

#### TODO
 - Tests
