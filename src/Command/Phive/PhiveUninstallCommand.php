<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard\Command\Phive;

use Ghostwriter\CodingStandard\Command\AbstractCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'ghostwriter:phive:uninstall', description: 'Use Phive to uninstall the project dependencies',)]
final class PhiveUninstallCommand extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Using Phive to uninstall the project dependencies...');

        if (! $input->hasOption('tool')) {
            $output->writeln('No tool specified, please provide a tool to install.');

            return 1;
        }

        return $this->processExecutor->execute(
            command: [
                'vendor/ghostwriter/coding-standard/tools/phive',
                'uninstall',
                '--no-progress',
                '--no-interaction',
                $input->getOption('tool')
            ],
        );
    }
}
