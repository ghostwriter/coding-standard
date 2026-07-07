<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\CodingStyle\Rector\ClassMethod\UnSpreadOperatorRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\PHPUnit\PHPUnit60\Rector\ClassMethod\AddDoesNotPerformAssertionToNonAssertingTestRector;
use Rector\Renaming\Rector\FileWithoutNamespace\PseudoNamespaceToNamespaceRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $directory = getcwd();
    $rectorConfig->importNames();
    $rectorConfig->importShortClasses();
    $rectorConfig->parallel();
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::DEAD_CODE,
        SetList::NAMING,
        SetList::PRIVATIZATION,
        SetList::TYPE_DECLARATION,
        SetList::EARLY_RETURN,
        SetList::PHP_82,
    ]);

    $paths = [];
    foreach ([
        $directory . '/ecs.php',
        $directory . '/config',
        $directory . '/data',
        $directory . '/module',
        $directory . '/public',
        $directory . '/src',
        $directory . '/test',
        $directory . '/tests',
        $directory . '/rector.php',
    ] as $path) {
        if (file_exists($path)) {
            $paths[] = $path;
        }
    }

    $skip = [];

    foreach ([
        $directory . '/test/Fixture/*',
        $directory . '/tests/Fixture/*',
        $directory . '/vendor/*',
    ] as $path) {
        $glob = glob($path);
        if ($glob === false) {
            continue;
        }

        if ($glob === []) {
            continue;
        }

        $skip[] = $path;
    }

    $rectorConfig->paths(array_unique([
        ...[
            $directory . '/bin',
            $directory . '/ecs.php',
            $directory . '/rector.php',
            $directory . '/src',
            $directory . '/tests',
        ],
        ...$paths,
    ]));

    $rectorConfig->phpVersion(PhpVersion::PHP_82);
    $rectorConfig->skip(array_unique([
        ...[
            $directory . '*/tests/Fixture/*',
            $directory . '*/vendor/*',
            CallableThisArrayToAnonymousFunctionRector::class,
            PseudoNamespaceToNamespaceRector::class,
            StringClassNameToClassConstantRector::class,
            AddDoesNotPerformAssertionToNonAssertingTestRector::class,
            UnSpreadOperatorRector::class,
        ],
        ...$skip,
    ]));
    // register single rule
};
