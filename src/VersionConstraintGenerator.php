<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard;

use InvalidArgumentException;

use function array_map;
use function explode;
use function implode;
use function mb_trim;
use function preg_match;
use function sprintf;
use function str_contains;
use function str_ends_with;
use function str_starts_with;

final class VersionConstraintGenerator
{
    public static function generateConstraint(string $constraint, string $newVersion): string
    {
        $constraint = mb_trim($constraint);

        $newVersion = self::normalize($newVersion);

        if (! self::isSemver($newVersion)) {
            return $constraint;
        }

        [$newMajor, $newMinor, $newPatch] = self::extractSemver($newVersion);
        $newConstraint = sprintf('~%d.%d.%d', $newMajor, $newMinor, $newPatch);

        if (0 === $newMajor && 0 === $newMinor && 0 === $newPatch) {
            return $newConstraint;
        }

        if ('' === $constraint || '*' === $constraint) {
            return $newConstraint;
        }

        $newMinorVersion = sprintf('%d.%d', $newMajor, $newMinor);
        $constraints = array_map('trim', explode('||', $constraint));

        foreach ($constraints as $index => $packageConstraint) {
            if (self::isSkippable($packageConstraint)) {
                continue;
            }

            if (self::extractMinorVersion($packageConstraint) !== $newMinorVersion) {
                continue;
            }

            $constraints[$index] = $newConstraint;

            return implode(' || ', $constraints);
        }

        $constraints[] = $newConstraint;

        return implode(' || ', $constraints);
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
