<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard\Command\Composer;

use Ghostwriter\CodingStandard\Command\AbstractCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'ghostwriter:composer:bump', description: 'Use composer to bump the project dependencies')]
final class ComposerBumpCommand extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Using Composer to bump the project dependencies...');

        return $this->processExecutor->execute(['vendor/ghostwriter/coding-standard/tools/composer', 'bump']);
    }
}
