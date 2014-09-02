<?php
date_default_timezone_set('Asia/Tokyo');
mb_language('Japanese');

$app['config.translator.lang'] = 'ja';

$app['config.debug_email_destination'] = 'your_email_address_here'; // null to not use.
