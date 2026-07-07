# Coding Standard

[![Compliance](https://github.com/ghostwriter/coding-standard/actions/workflows/compliance.yml/badge.svg)](https://github.com/ghostwriter/coding-standard/actions/workflows/compliance.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/coding-standard?color=8892bf)](https://www.php.net/supported-versions)
[![Latest Version on Packagist](https://badgen.net/packagist/v/ghostwriter/coding-standard)](https://packagist.org/packages/ghostwriter/coding-standard)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/coding-standard?color=blue)](https://packagist.org/packages/ghostwriter/coding-standard)

My personal PHP Coding Standard.

## Installation

You can install the package via composer:

``` bash
composer require ghostwriter/coding-standard --dev
```

## Usage

- Add configuration

```php.php
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->import(__DIR__ . '/vendor/ghostwriter/coding-standard/ecs.php');
    $ecsConfig->paths([__DIR__ . '/src', __DIR__ . '/tests']);
    // A. full sets
    $ecsConfig->sets([SetList::PSR_12]);

    // B. standalone rule
    $ecsConfig->ruleWithConfiguration(ArraySyntaxFixer::class, [
        'syntax' => 'short',
    ]);
};
```

## Command

``` bash
vendor/bin/ecs
```

``` bash
vendor/bin/ecs --fix
```

## Changelog

Please see [CHANGELOG.md](./CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email `nathanael.esayeas@protonmail.com` instead of using the issue tracker.

## Support

[[`Become a GitHub Sponsor`](https://github.com/sponsors/ghostwriter)]

## Credits

- [Nathanael Esayeas](https://github.com/ghostwriter)
- [friendsofphp/php-cs-fixer](https://github.com/friendsofphp/php-cs-fixer)
- [squizlabs/php_codesniffer](https://github.com/squizlabs/php_codesniffer)
- [slevomat/coding-standard](https://github.com/slevomat/coding-standard)
- [symplify/easy-coding-standard](https://github.com/symplify/easy-coding-standard)
- [All Contributors](https://github.com/ghostwriter/coding-standard/contributors)

## License

The BSD-3-Clause. Please see [License File](./LICENSE) for more information.
