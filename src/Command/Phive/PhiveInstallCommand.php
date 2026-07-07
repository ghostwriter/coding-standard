<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard\Command\Phive;

use Ghostwriter\CodingStandard\Command\AbstractCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'ghostwriter:phive:install', description: 'Use Phive to install the project dependencies',)]
final class PhiveInstallCommand extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Using Phive to install the project dependencies...');

        if (! $input->hasOption('tool')) {
            $output->writeln('No tool specified, please provide a tool to install.');

            return 1;
        }

        return $this->processExecutor->execute(
            command: [
                'vendor/ghostwriter/coding-standard/tools/phive',
                'install',
                '--trust-gpg-keys',
                '--no-progress',
                '--no-interaction',
                $input->getOption('tool')
            ],
        );
    }
}
