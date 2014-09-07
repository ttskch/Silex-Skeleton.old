<?php

use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->match('/', function (Request $request) use ($app) {

    /** @var $form \Symfony\Component\Form\Form */
    $form = require __DIR__ . '/forms/contact-form.php';

    $form->handleRequest($request);
    if ($form->isValid()) {

        // send email.
        $message = $app['twig_mailer']->buildMessage('emails/contact-form.txt.twig', $form);
        if ($app['twig_mailer']->send($message)) {
            $app['session']->getFlashBag()->add('success', 'Form submitted.');
            return $app->redirectByName('homepage');
        } else {
            $app['session']->getFlashBag()->add('danger', 'Email could not delivered.');
        }

    }
    return $app->render('index.html.twig', array(
        'form' => $form->createView(),
    ));
})
->bind('homepage')
;

$app->get('/pagination', function (Request $request) use ($app) {

    // sample data.
    $sampleData = array();
    for ($i = 1; $i <= 100; $i++) {
        $sampleData[] = array(
            'id' => $i,
            'hash' => sha1($i),
        );
    }

    // settings.
    $page = $request->query->get('page') ?: 1;
    $size = $request->query->get('size') ?: 25;
    $sort = $request->query->get('sort') ?: 'id';
    $direction = $request->query->get('direction') ?: 'asc';
    $pageRange = 5;

    // sort data.
    $columns = array();
    foreach ($sampleData as $index => $row) {
        foreach ($row as $key => $value) {
            $columns[$key][$index] = $value;
        }
    }
    array_multisort($columns[$sort], $direction === 'asc' ? SORT_ASC : SORT_DESC, SORT_NATURAL, $sampleData);

    // create pagination.
    $paginator = new Paginator();
    $pagination = $paginator->paginate($sampleData, $page, $size);
    $pagination->setPageRange($pageRange);
    $pagination->renderer = function ($data) use ($app) {
        return $app['twig']->render('paginations/default.html.twig', array(
            'data' => $data,
            'routing' => 'pagination',
        ));
    };

    return $app->render('pagination.html.twig', array(
        'pagination' => $pagination,
        'sort' => $sort,
        'direction' => array(
            'current' => $direction,
            'opposite' => $direction === 'asc' ? 'desc' : 'asc',
        ),
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
