<?php

declare(strict_types=1);

use Composer\Autoload\ClassLoader;
use Ghostwriter\Filesystem\Filesystem;

/** @var ClassLoader $classLoader */
$classLoader = require __DIR__ . '/vendor/autoload.php';

foreach (Filesystem::new()->recursiveIterator(__DIR__ . \DIRECTORY_SEPARATOR . 'functions') as $filePath) {
    require $filePath->toString();
}

return $classLoader;
