<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard\Command\Composer;

use Ghostwriter\CodingStandard\Command\AbstractCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'ghostwriter:composer:update', description: 'Use composer to update the project dependencies',)]
final class ComposerUpdateCommand extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Using Composer to update the project dependencies...');

        return $this->processExecutor->execute(
            command: [
                'vendor/ghostwriter/coding-standard/tools/composer',
                'update',
                '--with-dependencies',
                '--no-plugins',
                '--no-scripts',
                '--no-progress',
                '--no-interaction',
            ],
        );
    }
}
