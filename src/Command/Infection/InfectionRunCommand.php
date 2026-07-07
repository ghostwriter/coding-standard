<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard\Command\Infection;

use Ghostwriter\CodingStandard\Command\AbstractCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'ghostwriter:infection:run', description: 'Run the project\'s infection test suite')]
final class InfectionRunCommand extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Running the project's infection test suite...");

        return $this->processExecutor->execute(
            command: [
                'vendor/ghostwriter/coding-standard/tools/infection',
                '--min-covered-msi=0',
                '--min-msi=0',
                '--show-mutations',
                '--threads=max',
                '--verbose',
            ],
        );
    }
}
