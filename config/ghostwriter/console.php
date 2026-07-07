<?php

declare(strict_types=1);

use Ghostwriter\CodingStandard\Command\Composer\ComposerBumpCommand;
use Ghostwriter\CodingStandard\Command\Composer\ComposerUpdateCommand;
use Ghostwriter\CodingStandard\Command\ComposerRequireChecker\ComposerRequireCheckerCommand;
use Ghostwriter\CodingStandard\Command\ComposerUnused\ComposerUnusedCommand;
use Ghostwriter\CodingStandard\Command\Infection\InfectionRunCommand;
use Ghostwriter\CodingStandard\Command\Infection\InfectionUpdateConfigCommand;
use Ghostwriter\CodingStandard\Command\Phive\PhiveInstallCommand;
use Ghostwriter\CodingStandard\Command\Phive\PhiveUninstallCommand;
use Ghostwriter\CodingStandard\Command\Phive\PhiveUpdateCommand;
use Ghostwriter\CodingStandard\Command\PHPBench\PHPBenchCommand;
use Ghostwriter\CodingStandard\Command\PHPUnit\PHPUnitMigrateCommand;
use Ghostwriter\CodingStandard\Command\PHPUnit\PHPUnitTestCommand;
use Ghostwriter\CodingStandard\Command\Psalm\PsalmBaselineCommand;
use Ghostwriter\CodingStandard\Command\Psalm\PsalmCommand;
use Ghostwriter\CodingStandard\Command\Psalm\PsalmSecurityCommand;
use Symfony\Component\Console\Command\Command;

/**
 * @return array{
 *     name: string,
 *     package: string,
 *     auto_exit: bool,
 *     single_command: bool,
 *     default_command: string,
 *     catch_errors: bool,
 *     catch_exceptions: bool,
 *     commands: array<non-empty-string,class-string<Command>>
 * }
 */
return [
    'name' => 'Ghostwriter Coding Standard',
    'package' => 'ghostwriter/coding-standard',
    'auto_exit'       => false,
    'single_command'       => false,
    'default_command'  => 'list',
    'catch_errors'     => true,
    'catch_exceptions' => true,
    'commands' => [
        // 'command:name' => FullyQualifiedClassName::class,
        'ghostwriter:composer:bump' => ComposerBumpCommand::class,
        'ghostwriter:composer:update' => ComposerUpdateCommand::class,
        'ghostwriter:composer-require-checker' => ComposerRequireCheckerCommand::class,
        'ghostwriter:composer-unused' => ComposerUnusedCommand::class,
        'ghostwriter:infection:run' => InfectionRunCommand::class,
        'ghostwriter:infection:update-config' => InfectionUpdateConfigCommand::class,
        'ghostwriter:phpbench' => PHPBenchCommand::class,
        'ghostwriter:phpunit:migrate' => PHPUnitMigrateCommand::class,
        'ghostwriter:phpunit:test' => PHPUnitTestCommand::class,
        'ghostwriter:phive:install' => PhiveInstallCommand::class,
        'ghostwriter:phive:uninstall' => PhiveUninstallCommand::class,
        'ghostwriter:phive:update' => PhiveUpdateCommand::class,
        'ghostwriter:psalm:baseline' => PsalmBaselineCommand::class,
        'ghostwriter:psalm' => PsalmCommand::class,
        'ghostwriter:psalm:security' => PsalmSecurityCommand::class,
    ],
];
