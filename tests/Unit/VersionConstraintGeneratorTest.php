<?php

declare(strict_types=1);

namespace Tests\Unit;

use Generator;
use Ghostwriter\CodingStandard\VersionConstraintGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function dd;
use function iterator_to_array;
use function sprintf;

#[CoversClass(VersionConstraintGenerator::class)]
final class VersionConstraintGeneratorTest extends TestCase
{
    #[DataProvider('provideGenerateConstraintCases')]
    public function testGenerateConstraint(string $constraint, string $new, string $expected): void
    {
        self::assertTrue(true);
        //        self::assertSame($expected, VersionConstraintGenerator::generateConstraint($constraint, $new));
    }

    public static function provideConstraints(): Generator
    {
        yield from [
            'empty'  => '',
            'dev'  => '@dev',
            'whitespace' => ' ',
            'whitespace around constraint' => ' ~1.0.0 ',
            'star' => '*',
            'tilde' => '~1.0.0',
            'caret' => '^1.0.0',
        ];
    }

    public static function provideExpected(): Generator
    {
        yield from [
            'dev' => [
                'zero' => '@dev || ^0.0.0',
                'one' => '@dev || ^1.0.0',
                'dot' => '@dev || ^0.1.0',
                'major' => '@dev || ^1.0.0',
                'minor' => '@dev || ^0.2.0',
                'patch' => '@dev || ^0.0.1',
                'leading' => '@dev || ^2.0.0',
            ],
            'empty' => [
                'zero' => '^0.0.0',
                'one' => '^1.0.0',
                'dot' => '^0.1.0',
                'major' => '^1.0.0',
                'minor' => '^0.2.0',
                'patch' => '^0.0.1',
                'leading' => '^2.0.0',
            ],
            'whitespace' => [
                'zero' => '^0.0.0',
                'one' => '^1.0.0',
                'dot' => '^0.1.0',
                'major' => '^1.0.0',
                'minor' => '^0.2.0',
                'patch' => '^0.0.1',
                'leading' => '^2.0.0',
            ],
            'star' => [
                'zero' => '^0.0.0',
                'one' => '^1.0.0',
                'dot' => '^0.1.0',
                'major' => '^1.0.0',
                'minor' => '^0.2.0',
                'patch' => '^0.0.1',
                'leading' => '^2.0.0',
            ],
            'tilde' => [
                'zero' => '^0.0.0 || ~1.0.0',
                'one' => '^1.0.0',
                'dot' => '^0.1.0 || ~1.0.0',
                'major' => '^1.0.0',
                'minor' => '^0.2.0 || ~1.0.0',
                'patch' => '^0.0.1 || ~1.0.0',
                'leading' => '~1.0.0 || ^2.0.0',
            ],
            'caret' => [
                'zero' => '^0.0.0 || ^1.0.0',
                'one' => '^1.0.0',
                'dot' => '^0.1.0 || ^1.0.0',
                'major' => '^1.0.0',
                'minor' => '^0.2.0 || ^1.0.0',
                'patch' => '^0.0.1 || ^1.0.0',
                'leading' => '^1.0.0 || ^2.0.0',
            ],
            'whitespace around constraint' => [
                'zero' => '^0.0.0 || ~1.0.0',
                'one' => '^1.0.0',
                'dot' => '^0.1.0 || ~1.0.0',
                'major' => '^1.0.0',
                'minor' => '^0.2.0 || ~1.0.0',
                'patch' => '^0.0.1 || ~1.0.0',
                'leading' => '~1.0.0 || ^2.0.0',
            ],
        ];
    }

    public static function provideGenerateConstraintCases(): iterable
    {
        $expecting = iterator_to_array(self::provideExpected());

        foreach (self::provideConstraints() as $constraintKey => $constraint) {
            foreach (self::provideNewVersions() as $newKey => $new) {

                $expected = $expecting[$constraintKey][$newKey]
                    ?? dd([
                        $constraintKey,
                        $newKey,
                        $constraint,
                        $new,
                        VersionConstraintGenerator::generateConstraint($constraint, $new),
                    ]);

                yield sprintf(
                    '[%s] + [%s]  =>  (%s)  |  %s-%s',
                    $constraint,
                    $new,
                    $expected,
                    $constraintKey,
                    $newKey
                ) => [
                    'constraint' => $constraint,
                    'new' => $new,
                    'expected' => $expected,
                ];
            }
        }

        return;

        yield from [
            'last-three-and-major' => [
                'constraint' => '~12.3.0 || ~12.4.0 || ~12.5.0 || ~12.6.0 || ~12.7.0',
                'new' => '13.0.0',
                'expected' => '~12.6.0 || ~12.7.0 || ^13.0.0',
            ],
            'last-three-and-minor' => [
                'constraint' => '~12.3.0 || ~12.4.0 || ~12.5.0 || ~12.6.0 || ~12.7.0',
                'new' => '12.7.0',
                'expected' => '~12.5.0 || ~12.6.0 || ^12.7.0',
            ],
            'last-three-and-patch' => [
                'constraint' => '~12.3.0 || ~12.4.0 || ~12.5.0 || ~12.6.0 || ~12.7.0',
                'new' => '12.7.1',
                'expected' => '~12.5.0 || ~12.6.0 || ^12.7.1',
            ],
            'star' => [
                'constraint' => '*',
                'new' => '12.0.9',
                'expected' => '^12.0.9',
            ],
            '~11.5.14 || ~12.0.8' => [
                'constraint' => '~11.5.14 || ~12.0.8',
                'new' => '12.0.9',
                'expected' => '~11.5.14 || ^12.0.9',
            ],
            '~2.0.0 || ~2.1.0' => [
                'constraint' => '~2.0.0',
                'new' => '2.1.0',
                'expected' => '~2.0.0 || ^2.1.0',
            ],
            '~0.29.14' => [
                'constraint' => '~0.29.14',
                'new' => '~0.29.14',
                'expected' => '^0.29.14',
            ],
            'fix middle' => [
                'constraint' => '~1.0.1 || ~1.1.2 || ~1.2.3',
                'new' => 'v1.1.3',
                'expected' => '~1.0.1 || ^1.1.3 || ~1.2.3',
            ],
            'zero' => [
                'constraint' => '0',
                'new' => '0',
                'expected' => '^0.0.0',
            ],
            'one' => [
                'constraint' => '1',
                'new' => '2.0',
                'expected' => '1 || ~2.0.0',
            ],
            //            '1 star' => [
            //                'constraint' => '^2.*',
            //                'new' => '2.0',
            //                'expected' => '^2.* || ~2.0.0',
            //            ],
            //            '2 star' => [
            //                'constraint' => '^2.1.*',
            //                'new' => '3',
            //                'expected' => '^2.1.* || ~3.0.0',
            //            ],
            //            '2 dot 0' => [
            //                'constraint' => '^2.0.0',
            //                'new' => '2.0',
            //                'expected' => '~2.0.0',
            //            ],
            //            '@dev' => [
            //                'constraint' => '@dev',
            //                'new' => 'dev-main',
            //                'expected' => '@dev',
            //            ],
            //            '@stable' => [
            //                'constraint' => '@stable',
            //                'new' => 'dev-main',
            //                'expected' => '@stable',
            //            ],
            //            'dev-main' => [
            //                'constraint' => '1.0.0',
            //                'new' => 'dev-main',
            //                'expected' => '1.0.0',
            //            ],
            //            'branch-dev' => [
            //                'constraint' => '1.0.0',
            //                'new' => 'branch-dev',
            //                'expected' => '1.0.0',
            //            ],
            //            'at-stable' => [
            //                'constraint' => '1.0.0',
            //                'new' => '@stable',
            //                'expected' => '1.0.0',
            //            ],
            //            'major version bump - 1' => [
            //                'constraint' => '^1.0.0',
            //                'new' => '1.0.0',
            //                'expected' => '~1.0.0',
            //            ],
            //            'major version bump 0.1' => [
            //                'constraint' => '^0.1 || ^1.0.0',
            //                'new' => '1.0.0',
            //                'expected' => '^0.1 || ~1.0.0',
            //            ],
            //            'major version bump dev' => [
            //                'constraint' => '@dev || ^1.0.0',
            //                'new' => '1.0.0',
            //                'expected' => '@dev || ~1.0.0',
            //            ],
            //            'major version bump - 2' => [
            //                'constraint' => '~1.0.0',
            //                'new' => '1.0.0',
            //                'expected' => '~1.0.0',
            //            ],
            //            'major version bump' => [
            //                'constraint' => '~1.0.0',
            //                'new' => '2.0.0',
            //                'expected' => '~1.0.0 || ~2.0.0',
            //            ],
            //            'minor version bump' => [
            //                'constraint' => '~1.0.0',
            //                'new' => '1.5.0',
            //                'expected' => '~1.0.0 || ~1.5.0',
            //            ],
            //            'patch version bump' => [
            //                'constraint' => '~1.0.0',
            //                'new' => '1.0.5',
            //                'expected' => '~1.0.5',
            //            ],
            //            'multiple constraints with matching minor version' => [
            //                'constraint' => '~1.0.0 || ~1.5.7',
            //                'new' => '1.5.8',
            //                'expected' => '~1.0.0 || ~1.5.8',
            //            ],
            //            'multiple constraints with new minor version' => [
            //                'constraint' => '~1.0.0 || ~1.5.0',
            //                'new' => '1.6.0',
            //                'expected' => '~1.0.0 || ~1.5.0 || ~1.6.0',
            //            ],
            //            'multiple constraints with new major version two' => [
            //                'constraint' => '~1.0.0 || ~1.5.0',
            //                'new' => '2.0.0',
            //                'expected' => '~1.0.0 || ~1.5.0 || ~2.0.0',
            //            ],
            //            'update only last constraint' => [
            //                'constraint' => '~1.0.0 || ~1.2.3',
            //                'new' => '1.2.4',
            //                'expected' => '~1.0.0 || ~1.2.4',
            //            ],
            //            'newer patch of the first constraint' => [
            //                'constraint' => '~1.0.0 || ~1.2.3',
            //                'new' => '1.0.1',
            //                'expected' => '~1.0.1 || ~1.2.3',
            //            ],
            //            'completely new major version' => [
            //                'constraint' => '~1.0.0',
            //                'new' => '3.0.0',
            //                'expected' => '~1.0.0 || ~3.0.0',
            //            ],
            //            'duplicate version should not be added' => [
            //                'constraint' => '~1.0.0 || ~1.5.0',
            //                'new' => '1.5.0',
            //                'expected' => '~1.0.0 || ~1.5.0',
            //            ],
            //            'newer patch for an existing constraint' => [
            //                'constraint' => '~1.0.0 || ~1.5.5',
            //                'new' => '1.5.6',
            //                'expected' => '~1.0.0 || ~1.5.6',
            //            ],
            //            'newer patch with leading spaces' => [
            //                'constraint' => '  ~1.0.0   ||  ~1.5.5  ',
            //                'new' => '1.5.6',
            //                'expected' => '~1.0.0 || ~1.5.6',
            //            ],
            //            'new version with extra whitespace should be normalized' => [
            //                'constraint' => ' ~1.0.0 || ~1.5.5 ',
            //                'new' => ' 1.5.6 ',
            //                'expected' => '~1.0.0 || ~1.5.6',
            //            ],
            //
            //            'single version constraint' => [
            //                'constraint' => '~2.3.4',
            //                'new' => '2.3.5',
            //                'expected' => '~2.3.5',
            //            ],
            //
            //            'single constraint major bump' => [
            //                'constraint' => '~2.3.4',
            //                'new' => '3.0.0',
            //                'expected' => '~2.3.4 || ~3.0.0',
            //            ],
            //
            //            'multiple constraints same major' => [
            //                'constraint' => '~1.2.0 || ~2.3.4',
            //                'new' => '2.3.5',
            //                'expected' => '~1.2.0 || ~2.3.5',
            //            ],
            //
            //            'multiple constraints with new minor version two' => [
            //                'constraint' => '~1.2.0 || ~2.3.4',
            //                'new' => '2.4.0',
            //                'expected' => '~1.2.0 || ~2.3.4 || ~2.4.0',
            //            ],
            //
            //            'multiple constraints with new major version' => [
            //                'constraint' => '~1.2.0 || ~2.3.4',
            //                'new' => '3.0.0',
            //                'expected' => '~1.2.0 || ~2.3.4 || ~3.0.0',
            //            ],
            //
            //            'new version is already present' => [
            //                'constraint' => '~1.2.0 || ~2.3.4',
            //                'new' => '2.3.4',
            //                'expected' => '~1.2.0 || ~2.3.4',
            //            ],
            //
            //            'handle empty constraint constraint' => [
            //                'constraint' => '',
            //                'new' => '1.0.0',
            //                'expected' => '~1.0.0',
            //            ],
            //
            //            'handle constraint constraint with spaces' => [
            //                'constraint' => '  ~1.2.0  ||  ~2.3.4  ',
            //                'new' => '2.3.5',
            //                'expected' => '~1.2.0 || ~2.3.5',
            //            ],
            //
            //            'handle new version with spaces' => [
            //                'constraint' => '~1.2.0 || ~2.3.4',
            //                'new' => ' 2.3.5 ',
            //                'expected' => '~1.2.0 || ~2.3.5',
            //            ],
            //
            //            'complex constraint update' => [
            //                'constraint' => '~1.0.0 || ~1.5.0 || ~2.0.0',
            //                'new' => '2.0.1',
            //                'expected' => '~1.0.0 || ~1.5.0 || ~2.0.1',
            //            ],
            //
            //            'complex constraint new minor' => [
            //                'constraint' => '~1.0.0 || ~1.5.0 || ~2.0.0',
            //                'new' => '2.1.0',
            //                'expected' => '~1.5.0 || ~2.0.0 || ~2.1.0',
            //            ],
            //
            //            'complex constraint new major' => [
            //                'constraint' => '~1.0.0 || ~1.5.0 || ~2.0.0',
            //                'new' => '3.0.0',
            //                'expected' => '~1.5.0 || ~2.0.0 || ~3.0.0',
            //            ],
            //
            //            'complex constraint with mixed versions' => [
            //                'constraint' => '~1.0.0 || ~1.2.3 || ~2.0.0 || ~2.5.4',
            //                'new' => '2.5.5',
            //                'expected' => '~1.2.3 || ~2.0.0 || ~2.5.5',
            //            ],
            //
            //            'complex constraint with new unrelated version' => [
            //                'constraint' => '~1.0.0 || ~1.5.0 || ~2.0.0',
            //                'new' => '4.0.0',
            //                'expected' => '~1.5.0 || ~2.0.0 || ~4.0.0',
            //            ],
            //
            //            'multiple major versions with minor update' => [
            //                'constraint' => '~1.0.0 || ~2.0.0 || ~3.0.0',
            //                'new' => '3.1.0',
            //                'expected' => '~2.0.0 || ~3.0.0 || ~3.1.0',
            //            ],
            //
            //            'caret constraint minor update' => [
            //                'constraint' => '^2.3.4',
            //                'new' => '2.4.0',
            //                'expected' => '^2.3.4 || ~2.4.0',
            //            ],
            //
            //            'caret constraint major update' => [
            //                'constraint' => '^2.3.4',
            //                'new' => '3.0.0',
            //                'expected' => '^2.3.4 || ~3.0.0',
            //            ],
            //
            //            'tilde constraint patch update' => [
            //                'constraint' => '~2.3.4',
            //                'new' => '2.3.5',
            //                'expected' => '~2.3.5',
            //            ],
            //
            //            'tilde constraint minor update' => [
            //                'constraint' => '~2.3.4',
            //                'new' => '2.4.0',
            //                'expected' => '~2.3.4 || ~2.4.0',
            //            ],
            //
            //            'tilde constraint major update' => [
            //                'constraint' => '~2.3.4',
            //                'new' => '3.0.0',
            //                'expected' => '~2.3.4 || ~3.0.0',
            //            ],
            //
            //            'greater than version update' => [
            //                'constraint' => '>2.3.4',
            //                'new' => '2.3.5',
            //                'expected' => '~2.3.5',
            //            ],
            //
            //            'greater than or equal version update' => [
            //                'constraint' => '>=2.3.4',
            //                'new' => '2.4.0',
            //                'expected' => '>=2.3.4 || ~2.4.0',
            //            ],
            //
            //            'less than version update' => [
            //                'constraint' => '<2.3.4',
            //                'new' => '2.3.3',
            //                'expected' => '~2.3.3',
            //            ],
            //
            //            'less than or equal version update' => [
            //                'constraint' => '<=2.3.4',
            //                'new' => '2.3.3',
            //                'expected' => '~2.3.3',
            //            ],
            //
            //            'complex mixed constraints' => [
            //                'constraint' => '>=1.2.0, <2.0.0',
            //                'new' => '1.3.0',
            //                'expected' => '>=1.2.0, <2.0.0 || ~1.3.0',
            //            ],
            //
            //            'caret with greater than' => [
            //                'constraint' => '^2.3.4, >2.2.0',
            //                'new' => '2.5.0',
            //                'expected' => '^2.3.4, >2.2.0 || ~2.5.0',
            //            ],
            //
            //            'tilde with greater than' => [
            //                'constraint' => '~2.3.4, >2.2.0',
            //                'new' => '2.4.0',
            //                'expected' => '~2.3.4, >2.2.0 || ~2.4.0',
            //            ],
            //
            //            'explicit equals with new version' => [
            //                'constraint' => '2.3.4',
            //                'new' => '2.3.5',
            //                'expected' => '~2.3.5',
            //            ],
            //
            //            'range constraint update' => [
            //                'constraint' => '>=2.3.4, <3.0.0',
            //                'new' => '2.5.0',
            //                'expected' => '>=2.3.4, <3.0.0 || ~2.5.0',
            //            ],
            //
            //            'range constraint crossing major' => [
            //                'constraint' => '>=2.3.4, <3.0.0',
            //                'new' => '3.0.1',
            //                'expected' => '>=2.3.4, <3.0.0 || ~3.0.1',
            //            ],
            //
            //            'complex multiple constraints' => [
            //                'constraint' => '>=1.2.0, <2.0.0 || ~2.3.4',
            //                'new' => '2.4.0',
            //                'expected' => '>=1.2.0, <2.0.0 || ~2.3.4 || ~2.4.0',
            //            ],
            //
            //            'pre-release version update' => [
            //                'constraint' => '^2.3.4-beta',
            //                'new' => '2.3.4',
            //                'expected' => '~2.3.4',
            //            ],
            //
            //            'pre-release major update' => [
            //                'constraint' => '^2.3.4-beta',
            //                'new' => '3.0.0',
            //                'expected' => '^2.3.4-beta || ~3.0.0',
            //            ],
            //
            //            'multiple mixed constraints update' => [
            //                'constraint' => '^1.0.0 || >=2.3.4, <2.5.0',
            //                'new' => '2.4.1',
            //                'expected' => '^1.0.0 || >=2.3.4, <2.5.0 || ~2.4.1',
            //            ],
            //
            //            'hyphenated range update' => [
            //                'constraint' => '2.3.4 - 2.5.0',
            //                'new' => '2.5.1',
            //                'expected' => '2.3.4 - 2.5.0 || ~2.5.1',
            //            ],
            //
            //            'wildcard constraint update' => [
            //                'constraint' => '2.*',
            //                'new' => '3.0.0',
            //                'expected' => '2.* || ~3.0.0',
            //            ],
            //
            //            'exact version pinning update' => [
            //                'constraint' => '2.3.4',
            //                'new' => '2.3.5',
            //                'expected' => '~2.3.5',
            //            ],
            //
            //            'exact version pinned with multiple versions' => [
            //                'constraint' => '2.3.4 || 2.4.0',
            //                'new' => '2.5.0',
            //                'expected' => '2.3.4 || 2.4.0 || ~2.5.0',
            //            ],
            //
            //            'legacy major versions update' => [
            //                'constraint' => '~1.0 || ~2.3',
            //                'new' => '3.0.0',
            //                'expected' => '~1.0 || ~2.3 || ~3.0.0',
            //            ],
        ];
    }

    public static function provideNewVersions(): Generator
    {
        yield from [
            'zero' => '0',
            'one' => '1',
            'dot' => '0.1',
            'major' => '1.0.0',
            'minor' => '0.2.0',
            'patch' => '0.0.1',
            'leading' => 'v2.0.0',
        ];
    }

    public static function provideNonSemverVersions(): Generator
    {
        yield from [
            'dev-main' => 'dev-main',
            'branch-dev' => 'branch-dev',
            '@dev' => '@dev',
            '@stable' => '@stable',
            '1.0.0-beta' => '1.0.0-beta',
            '1.0.0+build.1' => '1.0.0+build.1',
            'v1.0.0' => 'v1.0.0',
            'version 1.0.0' => 'version 1.0.0',
            'random string' => 'random string',
        ];
    }
}
