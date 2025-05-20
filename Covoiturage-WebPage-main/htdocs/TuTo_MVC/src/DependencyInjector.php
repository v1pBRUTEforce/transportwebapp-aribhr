<?php

namespace App;

use App\Container;
use App\Controller;
use App\Models\Adresse;
use App\Models\Commentaire;
use App\Models\Logger;
use App\Models\Database;
use App\Models\Trajets;
use App\Models\User;

class DependencyInjector
{
    public static function build(string $controller): Controller
    {

        $container = new Container();

        $container->set('logger', function () {
            return new Logger();
        });



        $container->set('database', function ($container) {
            $config = $container->get('config');

            return new Database(
                $config['db']['host'],
                $config['db']['username'],
                $config['db']['password'],
                $config['db']['dbname']
            );
        });

        $container->set('config', function () {
            return [
                'db' => [
                    'host' => 'localhost',
                    'username' => 'root',
                    'password' => 'password',
                    'dbname' => 'mydatabase'
                ]
            ];
        });

        $logger = $container->get('logger');
        $db = $container->get('database');

        $args = [];
        $reflection = new \ReflectionClass($controller);
        $params = $reflection->getConstructor()->getParameters();
        foreach ($params as $param) {
            switch ($param->getType()) {
                case $logger::class:
                    $args[] = $logger;
                    break;
                case $db::class:
                    $args[] = $db;
                    break;
            }
        }

        return new $controller(...$args);
    }
}
