<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard;

use RuntimeException;

use const DIRECTORY_SEPARATOR;

use function defined;
use function getcwd;
use function str_replace;

final readonly class WindowsPathConverter
{
    /**
     * Convert a Unix-style path to a Windows-style path, on windows.
     *
     * @param string $unixPath the Unix-style path to convert
     *
     * @return string the Windows-style path
     */
    public function convert(string $unixPath): string
    {
        if (! defined('PHP_WINDOWS_VERSION_BUILD')) {
            return $unixPath;
        }

        $currentWorkingDirectory = getcwd();
        if (false === $currentWorkingDirectory) {
            throw new RuntimeException('Could not determine the current working directory.');
        }

        // Replace forward slashes with backslashes
        $windowsPath = str_replace('/', '\\', $unixPath);

        return $currentWorkingDirectory . DIRECTORY_SEPARATOR . $windowsPath;
    }
}
