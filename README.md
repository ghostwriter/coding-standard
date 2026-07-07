# Coding Standard

[![Compliance](https://github.com/ghostwriter/coding-standard/actions/workflows/compliance.yml/badge.svg)](https://github.com/ghostwriter/coding-standard/actions/workflows/compliance.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/coding-standard?color=8892bf)](https://www.php.net/supported-versions)
[![Latest Version on Packagist](https://badgen.net/packagist/v/ghostwriter/coding-standard)](https://packagist.org/packages/ghostwriter/coding-standard)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/coding-standard?color=blue)](https://packagist.org/packages/ghostwriter/coding-standard)

My personal PHP Coding Standard.

## Installation

You can install the package via composer:

``` bash
composer require ghostwriter/coding-standard:dev-main --dev
```

## Usage

- Create a `ecs.php` configuration file.

``` php
<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->import(__DIR__ . '/vendor/ghostwriter/coding-standard/ecs.php');
    $ecsConfig->paths([__DIR__ . '/rector.php', __DIR__ . '/ecs.php', __DIR__ . '/src', __DIR__ . '/tests']);
    $ecsConfig->skip([__DIR__ . '/vendor/*']);
};
```

- Create a `rector.php` configuration file.

``` php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/vendor/ghostwriter/coding-standard/rector.php');
    $rectorConfig->phpVersion(PhpVersion::PHP_81);

    $rectorConfig->paths([__DIR__ . '/src', __DIR__ . '/tests', __DIR__ . '/ecs.php', __DIR__ . '/rector.php']);
    $rectorConfig->skip([__DIR__ . '*/tests/Fixture/*', __DIR__ . '*/vendor/*']);
};
```

## Command

``` bash
vendor/bin/ecs
```

``` bash
vendor/bin/ecs --fix
```

``` bash
vendor/bin/rector
```

## Changelog

Please see [CHANGELOG.md](./CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email `nathanael.esayeas@protonmail.com` instead of using the issue tracker.

## Support

[[`Become a GitHub Sponsor`](https://github.com/sponsors/ghostwriter)]

## Credits

- [Nathanael Esayeas](https://github.com/ghostwriter)
- [All Contributors](https://github.com/ghostwriter/coding-standard/contributors)

## License

The BSD-3-Clause. Please see [License File](./LICENSE) for more information.
