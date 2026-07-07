<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard\Command\ComposerRequireChecker;

use Ghostwriter\CodingStandard\Command\AbstractCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'ghostwriter:composer-unused',
    description: 'Use ComposerUnused to check the project dependencies',
)]
final class ComposerUnusedCommand extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Using ComposerRequireChecker to check the project dependencies...');

        return $this->processExecutor->execute(
            command: [
                'vendor/ghostwriter/coding-standard/tools/composer-unused',
                '--excludePackage=phpunit/phpunit',
                '--excludePackage=vimeo/psalm',
                '--output-format=json',
                '--no-interaction',
                '--no-progress',
                '--no-ansi',
            ],
        );
    }
}
