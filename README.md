## Tactician Service Provider
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Atriedes/tactician-service-provider/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Atriedes/tactician-service-provider/?branch=master) [![Build Status](https://travis-ci.org/Atriedes/tactician-service-provider.svg?branch=master)](https://travis-ci.org/Atriedes/tactician-service-provider)
Provides Tactician as service to Pimple or Silex Container

### Requirements

* PHP > 5.5
* Silex >= 2.0
* Pimple >= 3.0

### Installation

`composer require jowy/tactician-service-provider`

### Usage

~~~php
/**
* Register handler in DI
* 
*  container id must be app.handler.CommandClassName without namespace
* /
$app["app.handler.CommandClassName"] = function() {
    return new \Package\Handler\HandlerClassName();
};

// Register Tactician Service
$app->register(
    new TacticianServiceProvider(),
    [
        "tactician.inflector" => "class_name",
        "tactician.middlewares" =>
            [
                function() {
                    return new LockingMiddleware();
                }
            ]
    ]
);
~~~

There is some for registering `Command Handler` class in `Pimple`

* Id must be `app.handler.CommandClassName` without full namespace
* Must return `Command Handler` object

### Options

#### Inflector

* class_name
* handle
* invoke

For more information for choosing `Inflector` please refer to [this documentation](http://tactician.thephpleague.com/tweaking-tactician/)

#### Middlewares

Must be in array that contain `Closure`, here some example if you want registering multiple `Middleware`

~~~php
$app->register(
    new TacticianServiceProvider(),
    [
        "tactician.inflector" => "class_name",
        "tactician.middlewares" =>
            [
                function() {
                    return new LockingMiddleware();
                },
                function() {
                    return new SomeMiddleware();
                },
                function() {
                    return new OtherMiddleware();
                }
            ]
    ]
);
~~~

### License

MIT, see LICENSE






