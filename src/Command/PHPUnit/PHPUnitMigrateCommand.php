<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard\Command\PHPUnit;

use Ghostwriter\CodingStandard\Command\AbstractCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'ghostwriter:phpunit:migrate',
    description: "Migrate the project's PHPUnit configuration to the latest version",
)]
final class PHPUnitMigrateCommand extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Migrating the project's PHPUnit configuration to the latest version...");

        return $this->processExecutor->execute(
            command: [
                'vendor/ghostwriter/coding-standard/tools/phpunit',
                '--colors=always',
                '--migrate-configuration',
            ],
        );
    }
}
