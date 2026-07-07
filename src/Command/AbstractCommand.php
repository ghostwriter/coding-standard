<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard\Command;

use Composer\Command\BaseCommand;
use Composer\Util\ProcessExecutor;
use Ghostwriter\CodingStandard\WindowsPathConverter;

abstract class AbstractCommand extends BaseCommand
{
    public function __construct(
        protected readonly ProcessExecutor $processExecutor,
        protected readonly WindowsPathConverter $windowsPathConverter,
    ) {
        parent::__construct(null);
    }
}
