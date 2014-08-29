<?php

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new SessionServiceProvider());

if (USE_DATABASE) {
    $app->register(new DoctrineServiceProvider(), [
        'db.options' => [
            'driver' => DB_DRIVER,
            'host' => DB_HOST,
            'dbname' => DB_NAME,
            'user' => DB_USER,
            'password' => DB_PASSWORD,
        ],
    ]);
}

if (USE_EMAIL) {
    $app->register(new Silex\Provider\SwiftmailerServiceProvider(), [
        'swiftmailer.options' => [
            'host' => SMTP_HOST,
            'port' => SMTP_PORT,
            'username' => SMTP_USER,
            'password' => SMTP_PASSWORD,
            'encryption' => SMTP_ENCRYPTION,
            'auth_mode' => SMTP_AUTH_MODE,
        ],
    ]);
}

$app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...
    $twig->addExtension(new \Twig_Extensions_Extension_Text($app));
    return $twig;
}));

return $app;
