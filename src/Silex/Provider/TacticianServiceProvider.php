<?php

namespace Silex\Provider;

use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Handler\MethodNameInflector\HandleClassNameInflector;
use League\Tactician\Handler\MethodNameInflector\HandleClassNameWithoutSuffixInflector;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Handler\MethodNameInflector\InvokeInflector;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;
use League\Tactician\Middleware;
use Silex\Application;
use Silex\Component\Tactician\CommandNameExtractor\Silex as SilexCommandExtractor;
use Silex\Component\Tactician\Locator\Silex as SilexLocator;
use Silex\ServiceProviderInterface;

/**
 * Class TacticianServiceProvider
 * @package Silex\Provider
 */
class TacticianServiceProvider implements ServiceProviderInterface
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $this->app = $app;

        foreach ($this->config as $key => $value) {
            $this->app[$key] = $value;
        }

        // register default locator if haven't defined yet
        if (! $app->offsetExists('tactician.locator')) {
            $app['tactician.locator'] = $app->share(function () use ($app) {
                return new SilexLocator($app);
            });
        }

        // register default command extractor if haven't defined yet
        if (! $app->offsetExists('tactician.command_extractor')) {
            $app['tactician.command_extractor'] = $app->share(function () {
                return new SilexCommandExtractor();
            });
        }

        // if inflector is string then resolve it
        if (is_string($app['tactician.inflector'])) {
            $app['tactician.inflector'] = $app->share(
                $this->resolveStringBaseMethodInflector($app['tactician.inflector'])
            );
        }

        $app['tactician.command_bus'] = $app->share(function () use ($app) {
            // type checking, make sure all command bus component are valid
            if (! $app['tactician.command_extractor'] instanceof CommandNameExtractor) {
                throw new \InvalidArgumentException(sprintf(
                    'Tactician command extractor must implement %s',
                    CommandNameExtractor::class
                ));
            }

            if (! $app['tactician.locator'] instanceof HandlerLocator) {
                throw new \InvalidArgumentException(sprintf(
                    'Tactician locator must implement %s',
                    HandlerLocator::class
                ));
            }

            if (! $app['tactician.inflector'] instanceof MethodNameInflector) {
                throw new \InvalidArgumentException(sprintf(
                    'Tactician inflector must implement %s',
                    MethodNameInflector::class
                ));
            }

            $handler_middleware = new CommandHandlerMiddleware(
                $app['tactician.command_extractor'],
                $app['tactician.locator'],
                $app['tactician.inflector']
            );

            // combine middleware together
            $middleware = $app['tactician.middleware'];
            array_walk($middleware, function (&$value) {
                $value = $this->resolveMiddleware($value);
            });
            array_push($middleware, $handler_middleware);

            return new CommandBus($middleware);
        });
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {

    }

    /**
     * @param string|Middleware $middleware
     * @return Middleware
     */
    private function resolveMiddleware($middleware)
    {
        if ($middleware instanceof Middleware) {
            return $middleware;
        }

        if ($this->app->offsetExists($middleware)) {
            $middleware = $this->app[$middleware];
            if ($middleware instanceof Middleware) {
                return $middleware;
            }
        }

        throw new \InvalidArgumentException(sprintf('Tactician middleware must implement %s', Middleware::class));
    }

    /**
     * @param $string
     * @return \Closure
     */
    private function resolveStringBaseMethodInflector($string)
    {
        switch ($string) {
            case 'class_name':
                $inflector = function () {
                    return new HandleClassNameInflector();
                };
                break;
            case 'class_name_without_suffix':
                $inflector = function () {
                    return new HandleClassNameWithoutSuffixInflector();
                };
                break;
            case 'handle':
                $inflector = function () {
                    return new HandleInflector();
                };
                break;
            case 'invoke':
                $inflector = function () {
                    return new InvokeInflector();
                };
                break;
            default:
                $inflector = function () {
                    return new HandleClassNameInflector();
                };
                break;
        }

        return $inflector;
    }
}
