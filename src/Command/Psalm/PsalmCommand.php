<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard\Command\Psalm;

use Ghostwriter\CodingStandard\Command\AbstractCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'ghostwriter:psalm', description: "Run Psalm to analyze the project's codebase",)]
final class PsalmCommand extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Running Psalm to analyze the project's codebase...");

        return $this->processExecutor->execute(
            command: ['vendor/ghostwriter/coding-standard/tools/psalm', '--stats', '--no-diff', '--no-cache'],
        );
    }
}
