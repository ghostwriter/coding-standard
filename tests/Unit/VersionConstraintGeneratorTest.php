<?php

declare(strict_types=1);

namespace Tests\Unit;

use Generator;
use Ghostwriter\CodingStandard\VersionConstraintGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(VersionConstraintGenerator::class)]
final class VersionConstraintGeneratorTest extends TestCase
{
    #[DataProvider('provideVersionConstraints')]
    public function testGenerateConstraint(string $constraint, string $new, string $expected): void
    {
        self::assertSame($expected, VersionConstraintGenerator::generateConstraint($constraint, $new));
    }

    public static function provideVersionConstraints(): Generator
    {
        yield from [
            '~11.5.14 || ~12.0.8' => [
                'constraint' => '~11.5.14 || ~12.0.8',
                'new' => '12.0.9',
                'expected' => '~11.5.14 || ~12.0.9',
            ],
            '~2.0.0 || ~2.1.0' => [
                'constraint' => '~2.0.0',
                'new' => '2.1.0',
                'expected' => '~2.0.0 || ~2.1.0',
            ],
            '~0.29.14' => [
                'constraint' => '~0.29.14',
                'new' => '~0.29.14',
                'expected' => '~0.29.14',
            ],
            'fix middle' => [
                'constraint' => '~1.0.1 || ~1.1.2 || ~1.2.3',
                'new' => 'v1.1.3',
                'expected' => '~1.0.1 || ~1.1.3 || ~1.2.3',
            ],
            'zero' => [
                'constraint' => '0',
                'new' => '0',
                'expected' => '~0.0.0',
            ],
            'one' => [
                'constraint' => '1',
                'new' => '2.0',
                'expected' => '1 || ~2.0.0',
            ],
            '1 star' => [
                'constraint' => '^2.*',
                'new' => '2.0',
                'expected' => '^2.* || ~2.0.0',
            ],
            '2 star' => [
                'constraint' => '^2.1.*',
                'new' => '3',
                'expected' => '^2.1.* || ~3.0.0',
            ],
            '2 dot 0' => [
                'constraint' => '^2.0.0',
                'new' => '2.0',
                'expected' => '~2.0.0',
            ],
            '@dev' => [
                'constraint' => '@dev',
                'new' => 'dev-main',
                'expected' => '@dev',
            ],
            '@stable' => [
                'constraint' => '@stable',
                'new' => 'dev-main',
                'expected' => '@stable',
            ],
            'dev-main' => [
                'constraint' => '1.0.0',
                'new' => 'dev-main',
                'expected' => '1.0.0',
            ],
            'branch-dev' => [
                'constraint' => '1.0.0',
                'new' => 'branch-dev',
                'expected' => '1.0.0',
            ],
            'at-stable' => [
                'constraint' => '1.0.0',
                'new' => '@stable',
                'expected' => '1.0.0',
            ],
            'major version bump - 1' => [
                'constraint' => '^1.0.0',
                'new' => '1.0.0',
                'expected' => '~1.0.0',
            ],
            'major version bump 0.1' => [
                'constraint' => '^0.1 || ^1.0.0',
                'new' => '1.0.0',
                'expected' => '^0.1 || ~1.0.0',
            ],
            'major version bump dev' => [
                'constraint' => '@dev || ^1.0.0',
                'new' => '1.0.0',
                'expected' => '@dev || ~1.0.0',
            ],
            'major version bump - 2' => [
                'constraint' => '~1.0.0',
                'new' => '1.0.0',
                'expected' => '~1.0.0',
            ],
            'major version bump' => [
                'constraint' => '~1.0.0',
                'new' => '2.0.0',
                'expected' => '~1.0.0 || ~2.0.0',
            ],
            'minor version bump' => [
                'constraint' => '~1.0.0',
                'new' => '1.5.0',
                'expected' => '~1.0.0 || ~1.5.0',
            ],
            'patch version bump' => [
                'constraint' => '~1.0.0',
                'new' => '1.0.5',
                'expected' => '~1.0.5',
            ],
            'multiple constraints with matching minor version' => [
                'constraint' => '~1.0.0 || ~1.5.7',
                'new' => '1.5.8',
                'expected' => '~1.0.0 || ~1.5.8',
            ],
            'multiple constraints with new minor version' => [
                'constraint' => '~1.0.0 || ~1.5.0',
                'new' => '1.6.0',
                'expected' => '~1.0.0 || ~1.5.0 || ~1.6.0',
            ],
            'multiple constraints with new major version two' => [
                'constraint' => '~1.0.0 || ~1.5.0',
                'new' => '2.0.0',
                'expected' => '~1.0.0 || ~1.5.0 || ~2.0.0',
            ],
            'update only last constraint' => [
                'constraint' => '~1.0.0 || ~1.2.3',
                'new' => '1.2.4',
                'expected' => '~1.0.0 || ~1.2.4',
            ],
            'newer patch of the first constraint' => [
                'constraint' => '~1.0.0 || ~1.2.3',
                'new' => '1.0.1',
                'expected' => '~1.0.1 || ~1.2.3',
            ],
            'completely new major version' => [
                'constraint' => '~1.0.0',
                'new' => '3.0.0',
                'expected' => '~1.0.0 || ~3.0.0',
            ],
            'duplicate version should not be added' => [
                'constraint' => '~1.0.0 || ~1.5.0',
                'new' => '1.5.0',
                'expected' => '~1.0.0 || ~1.5.0',
            ],
            'newer patch for an existing constraint' => [
                'constraint' => '~1.0.0 || ~1.5.5',
                'new' => '1.5.6',
                'expected' => '~1.0.0 || ~1.5.6',
            ],
            'newer patch with leading spaces' => [
                'constraint' => '  ~1.0.0   ||  ~1.5.5  ',
                'new' => '1.5.6',
                'expected' => '~1.0.0 || ~1.5.6',
            ],
            'new version with extra whitespace should be normalized' => [
                'constraint' => ' ~1.0.0 || ~1.5.5 ',
                'new' => ' 1.5.6 ',
                'expected' => '~1.0.0 || ~1.5.6',
            ],

            'single version constraint' => [
                'constraint' => '~2.3.4',
                'new' => '2.3.5',
                'expected' => '~2.3.5',
            ],

            'single constraint major bump' => [
                'constraint' => '~2.3.4',
                'new' => '3.0.0',
                'expected' => '~2.3.4 || ~3.0.0',
            ],

            'multiple constraints same major' => [
                'constraint' => '~1.2.0 || ~2.3.4',
                'new' => '2.3.5',
                'expected' => '~1.2.0 || ~2.3.5',
            ],

            'multiple constraints with new minor version two' => [
                'constraint' => '~1.2.0 || ~2.3.4',
                'new' => '2.4.0',
                'expected' => '~1.2.0 || ~2.3.4 || ~2.4.0',
            ],

            'multiple constraints with new major version' => [
                'constraint' => '~1.2.0 || ~2.3.4',
                'new' => '3.0.0',
                'expected' => '~1.2.0 || ~2.3.4 || ~3.0.0',
            ],

            'new version is already present' => [
                'constraint' => '~1.2.0 || ~2.3.4',
                'new' => '2.3.4',
                'expected' => '~1.2.0 || ~2.3.4',
            ],

            'handle empty constraint constraint' => [
                'constraint' => '',
                'new' => '1.0.0',
                'expected' => '~1.0.0',
            ],

            'handle constraint constraint with spaces' => [
                'constraint' => '  ~1.2.0  ||  ~2.3.4  ',
                'new' => '2.3.5',
                'expected' => '~1.2.0 || ~2.3.5',
            ],

            'handle new version with spaces' => [
                'constraint' => '~1.2.0 || ~2.3.4',
                'new' => ' 2.3.5 ',
                'expected' => '~1.2.0 || ~2.3.5',
            ],

            'complex constraint update' => [
                'constraint' => '~1.0.0 || ~1.5.0 || ~2.0.0',
                'new' => '2.0.1',
                'expected' => '~1.0.0 || ~1.5.0 || ~2.0.1',
            ],

            'complex constraint new minor' => [
                'constraint' => '~1.0.0 || ~1.5.0 || ~2.0.0',
                'new' => '2.1.0',
                'expected' => '~1.0.0 || ~1.5.0 || ~2.0.0 || ~2.1.0',
            ],

            'complex constraint new major' => [
                'constraint' => '~1.0.0 || ~1.5.0 || ~2.0.0',
                'new' => '3.0.0',
                'expected' => '~1.0.0 || ~1.5.0 || ~2.0.0 || ~3.0.0',
            ],

            'complex constraint with mixed versions' => [
                'constraint' => '~1.0.0 || ~1.2.3 || ~2.0.0 || ~2.5.4',
                'new' => '2.5.5',
                'expected' => '~1.0.0 || ~1.2.3 || ~2.0.0 || ~2.5.5',
            ],

            'complex constraint with new unrelated version' => [
                'constraint' => '~1.0.0 || ~1.5.0 || ~2.0.0',
                'new' => '4.0.0',
                'expected' => '~1.0.0 || ~1.5.0 || ~2.0.0 || ~4.0.0',
            ],

            'multiple major versions with minor update' => [
                'constraint' => '~1.0.0 || ~2.0.0 || ~3.0.0',
                'new' => '3.1.0',
                'expected' => '~1.0.0 || ~2.0.0 || ~3.0.0 || ~3.1.0',
            ],

            'caret constraint minor update' => [
                'constraint' => '^2.3.4',
                'new' => '2.4.0',
                'expected' => '^2.3.4 || ~2.4.0',
            ],

            'caret constraint major update' => [
                'constraint' => '^2.3.4',
                'new' => '3.0.0',
                'expected' => '^2.3.4 || ~3.0.0',
            ],

            'tilde constraint patch update' => [
                'constraint' => '~2.3.4',
                'new' => '2.3.5',
                'expected' => '~2.3.5',
            ],

            'tilde constraint minor update' => [
                'constraint' => '~2.3.4',
                'new' => '2.4.0',
                'expected' => '~2.3.4 || ~2.4.0',
            ],

            'tilde constraint major update' => [
                'constraint' => '~2.3.4',
                'new' => '3.0.0',
                'expected' => '~2.3.4 || ~3.0.0',
            ],

            'greater than version update' => [
                'constraint' => '>2.3.4',
                'new' => '2.3.5',
                'expected' => '~2.3.5',
            ],

            'greater than or equal version update' => [
                'constraint' => '>=2.3.4',
                'new' => '2.4.0',
                'expected' => '>=2.3.4 || ~2.4.0',
            ],

            'less than version update' => [
                'constraint' => '<2.3.4',
                'new' => '2.3.3',
                'expected' => '~2.3.3',
            ],

            'less than or equal version update' => [
                'constraint' => '<=2.3.4',
                'new' => '2.3.3',
                'expected' => '~2.3.3',
            ],

            'complex mixed constraints' => [
                'constraint' => '>=1.2.0, <2.0.0',
                'new' => '1.3.0',
                'expected' => '>=1.2.0, <2.0.0 || ~1.3.0',
            ],

            'caret with greater than' => [
                'constraint' => '^2.3.4, >2.2.0',
                'new' => '2.5.0',
                'expected' => '^2.3.4, >2.2.0 || ~2.5.0',
            ],

            'tilde with greater than' => [
                'constraint' => '~2.3.4, >2.2.0',
                'new' => '2.4.0',
                'expected' => '~2.3.4, >2.2.0 || ~2.4.0',
            ],

            'explicit equals with new version' => [
                'constraint' => '2.3.4',
                'new' => '2.3.5',
                'expected' => '~2.3.5',
            ],

            'range constraint update' => [
                'constraint' => '>=2.3.4, <3.0.0',
                'new' => '2.5.0',
                'expected' => '>=2.3.4, <3.0.0 || ~2.5.0',
            ],

            'range constraint crossing major' => [
                'constraint' => '>=2.3.4, <3.0.0',
                'new' => '3.0.1',
                'expected' => '>=2.3.4, <3.0.0 || ~3.0.1',
            ],

            'complex multiple constraints' => [
                'constraint' => '>=1.2.0, <2.0.0 || ~2.3.4',
                'new' => '2.4.0',
                'expected' => '>=1.2.0, <2.0.0 || ~2.3.4 || ~2.4.0',
            ],

            'pre-release version update' => [
                'constraint' => '^2.3.4-beta',
                'new' => '2.3.4',
                'expected' => '~2.3.4',
            ],

            'pre-release major update' => [
                'constraint' => '^2.3.4-beta',
                'new' => '3.0.0',
                'expected' => '^2.3.4-beta || ~3.0.0',
            ],

            'multiple mixed constraints update' => [
                'constraint' => '^1.0.0 || >=2.3.4, <2.5.0',
                'new' => '2.4.1',
                'expected' => '^1.0.0 || >=2.3.4, <2.5.0 || ~2.4.1',
            ],

            'hyphenated range update' => [
                'constraint' => '2.3.4 - 2.5.0',
                'new' => '2.5.1',
                'expected' => '2.3.4 - 2.5.0 || ~2.5.1',
            ],

            'wildcard constraint update' => [
                'constraint' => '2.*',
                'new' => '3.0.0',
                'expected' => '2.* || ~3.0.0',
            ],

            'exact version pinning update' => [
                'constraint' => '2.3.4',
                'new' => '2.3.5',
                'expected' => '~2.3.5',
            ],

            'exact version pinned with multiple versions' => [
                'constraint' => '2.3.4 || 2.4.0',
                'new' => '2.5.0',
                'expected' => '2.3.4 || 2.4.0 || ~2.5.0',
            ],

            'legacy major versions update' => [
                'constraint' => '~1.0 || ~2.3',
                'new' => '3.0.0',
                'expected' => '~1.0 || ~2.3 || ~3.0.0',
            ],
        ];
    }
}
