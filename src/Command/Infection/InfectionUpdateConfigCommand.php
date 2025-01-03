<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard\Command\Infection;

use Ghostwriter\CodingStandard\Command\AbstractCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function copy;
use function dirname;
use function file_exists;
use function getcwd;

#[AsCommand(
    name: 'ghostwriter:infection:update-config',
    description: 'Update the project\'s Infection configuration file'
)]
final class InfectionUpdateConfigCommand extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Updating the project's Infection configuration file...");

        $currentWorkingDirectory = getcwd();

        if (false === $currentWorkingDirectory) {
            $output->writeln('Unable to determine the current working directory.');

            return 1;
        }

        $fromInfectionConfigPath = dirname(__DIR__, 3) . '/infection.json5.dist';

        if (! file_exists($fromInfectionConfigPath)) {
            $output->writeln('The Infection stub configuration file does not exist. - ' . $fromInfectionConfigPath);

            return 1;
        }

        $toInfectionConfigPath = $currentWorkingDirectory . '/infection.json5.dist';

        if (! copy($fromInfectionConfigPath, $toInfectionConfigPath)) {
            $output->writeln('Unable to copy the Infection stub configuration file. - ' . $toInfectionConfigPath);

            return 1;
        }

        $output->writeln('The Infection stub configuration file has been copied.');

        return 0;
    }
}
