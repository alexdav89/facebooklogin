<?php

if(!session_id()) {
    session_start();
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/fbtest.php';

$app_id = '1853857884867993';

$fb = new Facebook\Facebook([
  'app_id' => $app_id ,
  'app_secret' => '',
  'default_graph_version' => 'v2.2',
  ]);



