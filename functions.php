<?php

declare(strict_types=1);

use Composer\Autoload\ClassLoader;

/** @var ClassLoader $classLoader */
$classLoader = require __DIR__ . '/vendor/autoload.php';

foreach (glob(__DIR__ . \DIRECTORY_SEPARATOR . 'functions' . \DIRECTORY_SEPARATOR . '*.php') as $filePath) {
    require $filePath;
}

foreach (glob(__DIR__ . \DIRECTORY_SEPARATOR . 'functions' . \DIRECTORY_SEPARATOR . '**'. \DIRECTORY_SEPARATOR . '*.php') as $filePath) {
    require $filePath;
}

return $classLoader;
