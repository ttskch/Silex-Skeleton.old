<?php

// configure your app for the production environment

$app['twig.path'] = array(__DIR__.'/../templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');

$app['twig.form.templates'] = array(
    'forms/form-horizontal-layout.html.twig',
);

$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->setFallbackLocale(array($app['config.translator.lang']));
    $translator->addResource(
        'xliff',
        __DIR__ . '/../vendor/symfony/validator/Symfony/Component/Validator/Resources/translations/validators.' . $app['config.translator.lang'] . '.xlf',
        $app['config.translator.lang']
    );
    return $translator;
}));

$app['db.options'] = $app['config.db.options'];

$app['swiftmailer.options'] = $app['config.swiftmailer.options'];
