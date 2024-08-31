<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard\Command\PHPUnit;

use Ghostwriter\CodingStandard\Command\AbstractCommand;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use const PHP_BINARY;

#[AsCommand(name: 'ghostwriter:phpunit:test', description: "Run the project's PHPUnit test suite",)]
final class PHPUnitTestCommand extends AbstractCommand
{
    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Running the project's PHPUnit test suite...");

        return $this->processExecutor->execute(
            command: [
                PHP_BINARY,
                $this->windowsPathConverter->convert('vendor/ghostwriter/coding-standard/tools/phpunit'),
                '--colors=always',
                '--do-not-cache-result',
                '--stop-on-failure',
                '--log-junit',
                $this->windowsPathConverter->convert('.cache/phpunit/junit.xml'),
            ]
        );
    }
}
