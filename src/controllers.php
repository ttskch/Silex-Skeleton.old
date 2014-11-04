<?php

use Knp\Component\Pager\Paginator;
use Quartet\Silex\Service\ArrayHandler;
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

$app->get('/pagination', function (Request $request) use ($app) {

    // sample data.
    $array = array();
    for ($i = 1; $i <= 100; $i++) {
        $array[] = array(
            'id' => $i,
            'value' => sha1($i),
        );
    }

    $page = $request->get('page', 1);
    $limit = $request->get('limit', 10);
    $sort = $request->get('sort', 'id');
    $direction = $request->get('direction') === 'desc' ? ArrayHandler::DESC : ArrayHandler::ASC;
    $filterField = $request->get('filterField');
    $filterValue = $request->get('filterValue');

    // sort and filter array.
    $array = $app['knp_paginator.array_handler']->filter($array, $filterField, $filterValue);
    $array = $app['knp_paginator.array_handler']->sort($array, $sort, $direction);

    $pagination = $app['knp_paginator']->paginate($array, $page, $limit);

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
