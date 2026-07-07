<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;

$workingDirectory = \getcwd() ?: __DIR__;

require_once $workingDirectory . '/vendor/autoload.php';

return ECSConfig::configure()->withSets(
    \array_filter(
        [
            \dirname($workingDirectory, 2) . '/ghostwriter/coding-standard/config/ecs.php',
            $workingDirectory . '/vendor/ghostwriter/coding-standard/config/ecs.php',
            $workingDirectory . '/config/ecs.php',
        ],
        static fn (string $path): bool => \file_exists($path)
    )
);
