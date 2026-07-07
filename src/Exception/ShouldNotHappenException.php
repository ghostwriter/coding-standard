<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard\Exception;

use Ghostwriter\CodingStandard\Interface\CodingStandardExceptionInterface;
use LogicException;

final class ShouldNotHappenException extends LogicException implements CodingStandardExceptionInterface {}
