<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard\Command\Phive;

use Ghostwriter\CodingStandard\Command\AbstractCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'ghostwriter:phive:update', description: 'Use Phive to update the project dependencies',)]
final class PhiveUpdateCommand extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Using Phive to update the project dependencies...');

        return $this->processExecutor->execute(
            command: [
                'vendor/ghostwriter/coding-standard/tools/phive',
                'update',
                '--trust-gpg-keys',
                '--no-progress',
                '--no-interaction',
            ],
        );
    }
}
