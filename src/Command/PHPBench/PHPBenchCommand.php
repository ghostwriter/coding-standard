<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard\Command\PHPBench;

use Ghostwriter\CodingStandard\Command\AbstractCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'ghostwriter:phpbench', description: 'Run PHPBench to benchmark the project',)]
final class PHPBenchCommand extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Running PHPBench to benchmark the project...');

        return $this->processExecutor->execute(
            command: [
                'vendor/ghostwriter/coding-standard/tools/phpbench',
                'run',
                '--revs=10',
                '--iterations=10',
                '--report=aggregate',
            ],
        );
    }
}
