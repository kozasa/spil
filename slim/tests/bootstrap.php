<?php

require __DIR__.'/../vendor/autoload.php'; // Composerのオートローダ

$kernel = \AspectMock\Kernel::getInstance();
$kernel->init([
    'debug' => true,
    'includePaths' => [__DIR__.'/../classes'],
    'cacheDir' => __DIR__.'/../cache/AspectMock',
]);