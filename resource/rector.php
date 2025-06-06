<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

$workingDirectory = \getcwd() ?: __DIR__;

require_once $workingDirectory . '/vendor/autoload.php';

return RectorConfig::configure()->withSets(
    \array_filter(
        [
            \dirname($workingDirectory, 2) . '/ghostwriter/coding-standard/config/rector.php',
            $workingDirectory . '/vendor/ghostwriter/coding-standard/config/rector.php',
            $workingDirectory . '/config/rector.php',
        ],
        static fn (string $path): bool => \file_exists($path)
    )
);
