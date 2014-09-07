<?php

use Quartet\Silex\Provider\TwigMailerServiceProvider;
use Silex\Application;
use Silex\Application\TranslationTrait;
use Silex\Application\TwigTrait;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class MyApplication extends Application
{
    use TwigTrait;
    use TranslationTrait;

    /**
     * @link https://github.com/silexphp/Silex/issues/678
     */
    public function redirectByName($name, $status = 302)
    {
        $parsed = explode('?', $name);
        $name = $parsed[0];
        $query = isset($parsed[1]) ? "?{$parsed[1]}" : '';
        $url = $this['url_generator']->generate($name) . $query;

        return $this->redirect($url, $status);
    }

    public function sendNamedFile($file, $filename = '', $filenameFallback = 'downloaded_file')
    {
        $response = new BinaryFileResponse($file);
        return $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename, $filenameFallback);
    }

    public function getBaseUrl()
    {
        return rtrim($this['url_generator']->getContext()->getBaseUrl(), '/') . '/';
    }
}

$app = new MyApplication();

$app->register(new UrlGeneratorServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new TranslationServiceProvider());
$app->register(new DoctrineServiceProvider());
$app->register(new SwiftmailerServiceProvider());
$app->register(new TwigMailerServiceProvider());

$app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...
    $twig->addExtension(new \Twig_Extensions_Extension_Text($app));
    return $twig;
}));

return $app;
