<?php

declare(strict_types=1);

namespace Tests\Unit;

use Ghostwriter\CodingStandard\Example;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Example::class)]
final class ExampleTest extends TestCase
{
    public function testExample(): void
    {
        self::assertInstanceOf(Example::class, new Example());
    }
}
