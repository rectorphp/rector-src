# Rector - Instant Upgrades and Automated Refactoring

[![Downloads](https://img.shields.io/packagist/dt/rector/rector.svg?style=flat-square)](https://packagist.org/packages/rector/rector)

<br>

This repository (`rectorphp/rector-src`) is for development Rector only.
Head to [`rectorphp/rector`](http://github.com/rectorphp/rector) for documentation, install or [creating an issue](https://github.com/rectorphp/rector/issues/new).

<br>

## Building `rectorphp/rector`

Code of this repository requires PHP 8. For `rector/rector` package the build downgrades code to PHP 7.4+.

<br>

## How to Contribute

Please read [contributing guideline](/CONTRIBUTING.md) for how to contribute to rector.

<br>

## Debug Tests

Do you need to measure speed of particular test? Or just check which test fixture is failing? Make use of pretty print:

```bash
 vendor/bin/phpunit -d --enable-pretty-print
```

<br>

## Code of Conduct

This project adheres to a [Contributor Code of Conduct](/CODE_OF_CONDUCT.md) By participating in this project and its community, you are expected to uphold this code.

<br>

## Rector Packages CI Status

**Symfony** - https://github.com/rectorphp/rector-symfony

* ![](https://github.com/rectorphp/rector-symfony/actions/workflows/tests.yaml/badge.svg)
![](https://github.com/rectorphp/rector-symfony/actions/workflows/code_analysis.yaml/badge.svg)

**PHPUnit** - https://github.com/rectorphp/rector-phpunit

* ![](https://github.com/rectorphp/rector-phpunit/actions/workflows/tests.yaml/badge.svg)
![](https://github.com/rectorphp/rector-phpunit/actions/workflows/code_analysis.yaml/badge.svg)

**Doctrine** - https://github.com/rectorphp/rector-doctrine

* ![](https://github.com/rectorphp/rector-doctrine/actions/workflows/tests.yaml/badge.svg)
![](https://github.com/rectorphp/rector-doctrine/actions/workflows/code_analysis.yaml/badge.svg)

**Downgrade PHP** - https://github.com/rectorphp/rector-downgrade-php

* ![](https://github.com/rectorphp/rector-downgrade-php/actions/workflows/tests.yaml/badge.svg)
![](https://github.com/rectorphp/rector-downgrade-php/actions/workflows/code_analysis.yaml/badge.svg)
