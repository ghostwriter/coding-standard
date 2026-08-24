<?php

declare(strict_types=1);

namespace Tests\Unit;

use Ghostwriter\CodingStandard\Example;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Example::class)]
final class ExampleTest extends AbstractTestCase
{
    public function testExample(): void
    {
        self::assertInstanceOf(Example::class, new Example());
    }
}
