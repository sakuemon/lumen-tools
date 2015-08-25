# Lumen Tools

Simple Tools for [Lumen](http://lumen.laravel.com/)

# Tools

## ResourceRouteAdder

A Restful resource route adder.

### Simple usage

```php
$routeAdder = new RestfulRouteAdder($app); // $app = lumen application
$routeAdder->add('/your_url', '\ControllerName\With\Namespace', 'named_route', ['allow', 'methods']);
```

### With group route

```php
$routeAdder = new RestfulRouteAdder($app);
$routeAdder->add('/your_url', '\ControllerName', 'named_route');

$app->group(['namespace' => 'Your\Namespace'], function () use ($routeAdder) {
    $routeAdder->register();
});
```

# Installation


