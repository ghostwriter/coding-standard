<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard;

use Composer\Package\Link;
use Generator;
use InvalidArgumentException;

use const PHP_EOL;

use function array_map;
use function array_merge;
use function array_slice;
use function explode;
use function implode;
use function mb_ltrim;
use function mb_substr;
use function mb_trim;
use function preg_match;
use function sprintf;
use function str_contains;
use function str_ends_with;
use function str_starts_with;
use function usort;
use function version_compare;

final class VersionConstraintGenerator
{
    /**
     * @param array<string,Link> $links
     *
     * @return Generator<string,string>
     */
    public static function extractVersions(array $links): Generator
    {
        foreach ($links as $packageName => $required) {
            if (self::skipPackage($packageName)) {
                echo (sprintf('Skipping package %s', $packageName)), PHP_EOL;

                continue;
            }

            if (! $required instanceof Link) {
                continue;
            }

            $constraint = $required->getConstraint()->getPrettyString();
            if (self::skipConstraint($constraint)) {
                echo (sprintf('Skipping constraint %s for package %s', $constraint, $packageName)), PHP_EOL;

                continue;
            }

            yield $packageName => $constraint;
        }
    }

    public static function generateConstraint(string $constraint, string $version, string $prefix = '^'): string
    {
        $constraint = mb_trim($constraint);

        $version = self::normalize($version);

        if (! self::isSemver($version)) {
            return $constraint;
        }

        [$newMajor, $newMinor, $newPatch] = self::extractSemver($version);

        $newConstraint = sprintf($prefix . '%d.%d.%d', $newMajor, $newMinor, $newPatch);

        if (0 === $newMajor && 0 === $newMinor && 0 === $newPatch) {
            return $newConstraint;
        }

        if ('' === $constraint || '*' === $constraint) {
            return $newConstraint;
        }

        $newMinorVersion = sprintf('%d.%d', $newMajor, $newMinor);
        $updatedConstraints = [];
        $semverConstraints = [];

        foreach (array_map('mb_trim', explode('||', $constraint)) as $packageConstraint) {
            if (self::skip($packageConstraint)) {
                $updatedConstraints[] = $packageConstraint;

                continue;
            }

            if (self::extractMinorVersion($packageConstraint) === $newMinorVersion) {
                continue;
            }

            $semverConstraints[] = $packageConstraint;
        }

        // Add the new constraint
        $semverConstraints[] = $newConstraint;

        // Extract semver parts and sort constraints to keep the latest three
        usort(
            $semverConstraints,
            static fn (string $left, string $right): int => version_compare(
                self::extractSemverParts($left),
                self::extractSemverParts($right),
            )
        );

        // Keep only the latest constraints
        $semverConstraints = array_slice($semverConstraints, -1);

        // Merge non-semver and semver constraints
        $updatedConstraints = array_merge($updatedConstraints, $semverConstraints);

        return implode(' || ', $updatedConstraints);
    }

    private static function extractMinorVersion(string $constraint): string
    {
        preg_match('#(\d+(?:\.[*|\d]+)?)#', $constraint, $matches);

        return $matches[1] ?? throw new InvalidArgumentException(sprintf('Invalid constraint: %s', $constraint));
    }

    private static function extractSemver(string $version): array
    {
        $trimmed = self::trim($version);

        preg_match('#^(\d+).?(\d+|\*|x)?.?(\d+)?#u', $trimmed, $matches);

        return [
            (int) $matches[1] ?? throw new InvalidArgumentException(sprintf('Invalid major version: %s', $version)),
            (int) $matches[2] ?? 0,
            (int) $matches[3] ?? 0,
        ];
    }

    private static function extractSemverParts(string $constraint): string
    {
        preg_match('#((\d+).?(\d+)?.?(\d+)?)#', $constraint, $matches);

        return $matches[1]  ??  throw new InvalidArgumentException(sprintf('Invalid constraint: %s', $constraint));
    }

    private static function isSemver(string $version): bool
    {
        return preg_match('#\d+\.\d+\.\d+#', $version) === 1;
    }

    private static function normalize(string $version): string
    {
        $trimmedVersion = self::trim($version);

        return match (1) {
            // if new version is missing patch number (e.g. 2.0) we add .0 to make it 2.0.0
            preg_match('#^\d+\.\d+$#', $trimmedVersion) => $trimmedVersion . '.0',
            // if new version is missing minor number (e.g. 2) we add .0.0 to make it 2.0.0
            preg_match('#^\d+$#', $trimmedVersion) => $trimmedVersion . '.0.0',
            // if new version is missing patch number with x (e.g. 2.*.*) we add .0 to make it 2.0.0
            preg_match('#^\d+\.\*\.\*\$#', $trimmedVersion) => mb_substr($trimmedVersion, 0, -4) . '0.0',
            // if new version is missing patch number with x (e.g. 2.1.*) we add .0 to make it 2.1.0
            preg_match('#^\d+\.\d+\.\*\$#', $trimmedVersion) => mb_substr($trimmedVersion, 0, -2) . '0',
            // if new version is missing minor number with x (e.g. 2.*) we add .0.0 to make it 2.0.0
            preg_match('#^\d+\.\*\$#', $trimmedVersion) => mb_substr($trimmedVersion, 0, -2) . '0.0',
            // if new version is already in full format (e.g. 2.0.0 or dev-main or *) we return it as is
            default => $trimmedVersion,
        };
    }

    private static function skip(string $constraint): bool
    {
        return match (true) {
            str_contains($constraint, '-dev'),
            str_contains($constraint, '@'),
            str_contains($constraint, 'dev-'),
            str_contains($constraint, '|'),
            str_ends_with($constraint, '-dev'),
            str_starts_with($constraint, 'dev-') => true,
            default => false,
        };
    }

    private static function skipConstraint(string $constraint): bool
    {
        // only accept standard version constraints, ignoring "dev-*" and "*-dev", etc.
        return match (true) {
            str_ends_with($constraint, '-dev'),
            str_starts_with($constraint, 'dev-') => true,
            default => false,
        };
    }

    private static function skipPackage(string $package): bool
    {
        // $skip = true;
        // only accept packages with `/` separator,
        //  ignoring "php", "ext-*", "lib-*", etc.
        return match (true) {
            str_contains($package, '/') => match (true) {
                str_starts_with($package, 'psr/') => true,
                default => false,
            },
            default => true,
        };
    }

    private static function trim(string $version): string
    {
        return mb_ltrim($version, 'vV~^');
    }
}
