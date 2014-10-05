<?php

use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {

    return $app->render('index.html.twig');
})
->bind('homepage')
;

$app->match('/form', function (Request $request) use ($app) {

    /** @var $form \Symfony\Component\Form\Form */
    $form = require __DIR__ . '/forms/contact-form.php';

    $form->handleRequest($request);
    if ($form->isValid()) {

        // send email.
        $message = $app['twig_message']->buildMessage('emails/contact-form.txt.twig', array(), $form);
        $app['session']->getFlashBag()->add('success', 'Form submitted.');
        return $app->redirectByName('form');
    }

    return $app['twig']->render('form.html.twig', array(
        'form' => $form->createView(),
    ));
})
->bind('form')
;

$app->get('/pagination', function () use ($app) {

    // sample data.
    $sampleData = array();
    for ($i = 1; $i <= 100; $i++) {
        $sampleData[] = array(
            'id' => $i,
            'value' => sha1($i),
        );
    }

    $pagination = $app['pagination']->paginate($sampleData);

    return $app['twig']->render('pagination.html.twig', array(
        'pagination' => $pagination,
    ));
})
->bind('pagination')
;

$app->error(function (\Exception $e, $code) use ($app) {

    if ($app['debug']) {
        return;
    }

    // 404.html.twig, or 40x.html.twig, or 4xx.html.twig, or error.html.twig
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
