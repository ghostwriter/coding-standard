<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;

$workingDirectory = \getcwd() ?: __DIR__;

$existingPaths = \array_filter(
    [$workingDirectory . '/config', $workingDirectory . '/src', $workingDirectory . '/tests'],
    static fn (string $path) => \file_exists($path)
);

$existingSkips = \array_merge(
    \array_filter([$workingDirectory . '/tests/Fixture'], static fn (string $path) => \file_exists($path)),
    ['Fixture', 'Analyzer']
);

return RectorConfig::configure()
    ->withPaths($existingPaths)
    ->withSkip($existingSkips)
    ->withPhpSets(php82: true)
    ->withRules([AddVoidReturnTypeWhereNoReturnRector::class]);
