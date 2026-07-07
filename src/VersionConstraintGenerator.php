<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard;

use InvalidArgumentException;

use function array_map;
use function array_merge;
use function array_slice;
use function explode;
use function implode;
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
    public static function generateConstraint(string $constraint, string $version): string
    {
        $constraint = mb_trim($constraint);

        $version = self::normalize($version);

        if (! self::isSemver($version)) {
            return $constraint;
        }

        [$newMajor, $newMinor, $newPatch] = self::extractSemver($version);
        $newConstraint = sprintf('^%d.%d.%d', $newMajor, $newMinor, $newPatch);

        //        if (0 === $newMajor && 0 === $newMinor && 0 === $newPatch) {
        //            return $constraint;
        //        }

        if ('' === $constraint || '*' === $constraint) {
            return $newConstraint;
        }

        $newMinorVersion = sprintf('%d.%d', $newMajor, $newMinor);
        $updatedConstraints = [];
        $semverConstraints = [];

        $constraints = array_map('trim', explode('||', $constraint));
        foreach ($constraints as $packageConstraint) {
            if (self::isSkippable($packageConstraint)) {
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

        $semverConstraints = array_slice($semverConstraints, -3);

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
        preg_match('#(\d+).?(\d+)?.?(\d+)?#', $version, $matches);

        return [
            (int) $matches[1] ?? throw new InvalidArgumentException(sprintf('Invalid major version: %s', $version)),
            (int) $matches[2] ?? throw new InvalidArgumentException(sprintf('Invalid minor version: %s', $version)),
            (int) $matches[3] ?? throw new InvalidArgumentException(sprintf('Invalid patch version: %s', $version)),
        ];
    }

    private static function extractSemverParts(string $constraint): string
    {
        preg_match('#(\d+\.\d+\.\d+)#', $constraint, $matches);

        return $matches[1] ?? '0.0.0';
    }

    private static function isSemver(string $version): bool
    {
        return preg_match('#\d+\.\d+\.\d+#', $version) === 1;
    }

    private static function isSkippable(string $constraint): bool
    {
        return match (true) {
            str_starts_with($constraint, 'dev-'),
            str_ends_with($constraint, '-dev'),
            str_contains($constraint, '@'),
            str_contains($constraint, '-dev'),
            str_contains($constraint, 'dev-') => true,
            default => false,
        };
    }

    private static function normalize(string $version): string
    {
        $trimmedVersion = mb_trim($version);

        return match (1) {
            // if new version is missing patch number (e.g. 2.0) we add .0 to make it 2.0.0
            preg_match('#^\d+\.\d+$#', $trimmedVersion) => $trimmedVersion . '.0',
            // if new version is missing minor number (e.g. 2) we add .0.0 to make it 2.0.0
            preg_match('#^\d+$#', $trimmedVersion) => $trimmedVersion . '.0.0',
            // if new version is already in full format (e.g. 2.0.0 or dev-main ) we return it as is
            default => $trimmedVersion,
        };
    }
}
