<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard\Command;

use Composer\Command\BaseCommand;
use Composer\Util\ProcessExecutor;

abstract class AbstractCommand extends BaseCommand
{
    public function __construct(
        protected readonly ProcessExecutor $processExecutor,
    ) {
        parent::__construct(null);
    }
}
