## Tactician Service Provider
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Atriedes/tactician-service-provider/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Atriedes/tactician-service-provider/?branch=master) [![Build Status](https://travis-ci.org/Atriedes/tactician-service-provider.svg?branch=master)](https://travis-ci.org/Atriedes/tactician-service-provider)

Provides Tactician as service to Pimple or Silex Container

### Requirements

* PHP >= 5.5
* Silex >= 2.0
* Pimple >= 3.0

### Installation

`composer require jowy/tactician-service-provider`

### Usage

#### Register tactician service provider

```php
$app->register(
    new TacticianServiceProvider(
        [
            'tactician.inflector' => 'class_name',
            'tactician.middleware' =>
                [
                    new LockingMiddleware()
                ]
       ]
   )
);
```

#### Register command handler in DIC

Handler must registered in container and use `FQCN` as service id

```php
$app[HandlerClass::class] = function() {
    return new HandlerClass();
};
```

#### Map command and handler

after tactician commadn bus service provider registered, you can map command and handler

```php
$app['tactician.locator']->addHandler(CommandClass::class, HandlerClass::class);
```

#### Dispatching Command

```php
$command = new CommandClass('param');

$container['tactician.command_bus']->handle($command)
```

### Options

#### Inflector

* class_name
* class_name_without_suffix
* handle
* invoke

For more information for choosing `Inflector` please refer to [this documentation](http://tactician.thephpleague.com/tweaking-tactician/)

#### Middleware

Middleware can be added while tactician service registered was registered.

```php
$app->register(
    new TacticianServiceProvider(
        [
            'tactician.inflector' => 'class_name',
            'tactician.middleware' =>
                [
                    new LockingMiddleware(),
                    new SomeMiddleware(),
                    new OtherMiddleware()
                ]
        ]
    )
);
```

Optionally lazy initialization for middleware is possible by using this method

```php
$app[LockingMiddleware::class] = function () {
    return new LockingMiddleware();
};

$app->register(
    new TacticianServiceProvider(
        [
            'tactician.inflector' => 'class_name',
            'tactician.middleware' =>
                [
                    LockingMiddleware::class
                ]
        ]
    )
);
```

### License

MIT, see LICENSE
