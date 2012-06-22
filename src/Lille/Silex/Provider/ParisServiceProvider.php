<?php

namespace Lille\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use J4mie\Paris\ORMWrapper as ORM;

class ParisServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['paris'] = $app->share(function () use ($app)
        {
            ORM::configure($app['paris.dsn']);
            ORM::configure('username', $app['paris.username']);
            ORM::configure('password', $app['paris.password']);
            ORM::configure('logging', true);
            ORM::configure('driver_options', array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            ));

            return new ParisWrapper();
        });
    }

    /** {@inheritDoc} */
    public function boot(Application $app)
    {
    }
}
