# CodingStandard

[![GitHub Sponsors](https://img.shields.io/github/sponsors/ghostwriter?label=Sponsor+@ghostwriter/coding-standard&logo=GitHub+Sponsors)](https://github.com/sponsors/ghostwriter)
[![Automation](https://github.com/ghostwriter/coding-standard/actions/workflows/automation.yml/badge.svg)](https://github.com/ghostwriter/coding-standard/actions/workflows/automation.yml)
[![Supported PHP Version](https://badgen.net/packagist/php/ghostwriter/coding-standard?color=8892bf)](https://www.php.net/supported-versions)
[![Downloads](https://badgen.net/packagist/dt/ghostwriter/coding-standard?color=blue)](https://packagist.org/packages/ghostwriter/coding-standard)

## Installation

You can install the package via composer:

``` bash
composer require ghostwriter/coding-standard:dev-main --dev
```

## Usage

```sh
composer ghostwriter:infection:run             Run the project's Infection test suite
composer ghostwriter:infection:update-config   Update the project's Infection configuration file

composer ghostwriter:phpunit:migrate           Migrate the project's PHPUnit configuration to the latest version
composer ghostwriter:phpunit:test              Run the project's PHPUnit test suite

composer ghostwriter:psalm                     Run Psalm to analyze the project's codebase
composer ghostwriter:psalm:baseline            Use Psalm to create a baseline for the project's codebase
composer ghostwriter:psalm:update     Use Psalm to update the baseline for the project's codebase
composer ghostwriter:psalm:security            Use Psalm to analyze the project's codebase for security issues
```

## Credits

- [`Nathanael Esayeas`](https://github.com/ghostwriter)
- [`phive`](https://github.com/phar-io/phive)
- [`All Contributors`](https://github.com/ghostwriter/coding-standard/contributors)

### Changelog

Please see [CHANGELOG.md](./CHANGELOG.md) for more information on what has changed recently.

### License

Please see [LICENSE](./LICENSE) for more information on the license that applies to this project.

### Security

Please see [SECURITY.md](./SECURITY.md) for more information on security disclosure process.
