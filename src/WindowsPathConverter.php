<?php

declare(strict_types=1);

namespace Ghostwriter\CodingStandard;

final readonly class WindowsPathConverter
{
    /**
     * Convert a Unix-style path to a Windows-style path.
     *
     * @param string $unixPath The Unix-style path to convert.
     * @return string The Windows-style path.
     */
    public function convert(
        string $unixPath,
    ): string
    {
        if (!\defined('PHP_WINDOWS_VERSION_BUILD')) {
            return $unixPath;
        }

        // Replace forward slashes with backslashes
        $windowsPath = str_replace('/', '\\', $unixPath);

        return ltrim($windowsPath, '\\');
    }

}
