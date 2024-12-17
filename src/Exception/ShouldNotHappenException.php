<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard\Exception;

use Ghostwriter\CodingStandard\Interface\CodingStandardExceptionInterface;

final class ShouldNotHappenException  extends \LogicException implements CodingStandardExceptionInterface
{
}
