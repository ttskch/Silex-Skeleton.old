<?php
date_default_timezone_set('Asia/Tokyo');
mb_language('Japanese');

$app['config.translator.lang'] = 'ja';

$app['knp_paginator.options'] = array(
    'template' => array(
        'pagination' => '@qck_silex_pagination/pagination-bootstrap3.html.twig',
        'filtration' => '@qck_silex_pagination/filtration-bootstrap3.html.twig',
    ),
);
