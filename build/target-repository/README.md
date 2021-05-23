# Rector - Instant Upgrades and Automated Refactoring

[![Downloads](https://img.shields.io/packagist/dt/rector/rector.svg?style=flat-square)](https://packagist.org/packages/rector/rector)

<br>

Rector helps you with 2 areas - major code changes and in daily work.

- Do you want to be constantly on the latest PHP/framework version without effort?
  → **Rector gets you there with instant upgrade**.

<br>

- Do you have code quality you need, but struggle to keep it with new developers in your team? Do you want continuous   code-reviews and delegate boring work?
  → **Add Rector to you CI and let it fix your code for you. Get [instant feedback](https://tomasvotruba.com/blog/2020/01/13/why-is-first-instant-feedback-crucial-to-developers/) after each commit.**

<br>

Rector is a tool that [we develop](https://getrector.org/) and share for free, so anyone can automate their refactoring.

Don't have time  you want to learn Rector faster, add it to your CI or cleanup your code base? [Hire us](https://getrector.org/contact) to help you.

<br>

## Supported Projects

Rector **instantly upgrades and refactors the PHP code of your application**.
It supports all versions of PHP from 5.3 and major open-source projects - [Symfony](https://github.com/rectorphp/rector-symfony), [PHPUnit](https://github.com/rectorphp/rector-phpunit), [Nette](https://github.com/rectorphp/rector-nette), [Laravel](https://github.com/rectorphp/rector-laravel), [CakePHP](https://github.com/rectorphp/rector-cakephp), [Doctrine](https://github.com/rectorphp/rector-doctrine) and [Typo3](https://github.com/sabbelasichon/typo3-rector) out of the box.

<br>

## Documentation

- [Explore 450+ Rector Rules](/docs/rector_rules_overview.md)
- [Auto Import Names](/docs/auto_import_names.md)
- [How to Ignore Rule or Paths](/docs/how_to_ignore_rule_or_paths.md)
- [Static Reflection and Autoload](/docs/static_reflection_and_autoload.md)
- [How to Configure Rule](/docs/how_to_configure_rules.md)

### For Rule Developers and Contributors

- [How Does Rector Work?](/docs/how_it_works.md)
- [PHP Parser Nodes](https://github.com/rectorphp/php-parser-nodes-docs/)
- [How to Work with Doc Block and Comments](/docs/how_to_work_with_doc_block_and_comments.md)
- [How to Generate New Rector Rule](/docs/create_own_rule.md)
- [How to add Test for Rector Rule](/docs/how_to_add_test_for_rector_rule.md)

<br>

## Install

```bash
composer require rector/rector --dev
```

<br>

## Running Rector

There a 2 main ways to use Rector:

- a *single rule*, to have the change under control
- or group of rules called *sets*

To use them, create a `rector.php` in your root directory:

```bash
vendor/bin/rector init
```

And modify it:

```php
// rector.php
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // here we can define, what sets of rules will be applied
    // tip: use "SetList" class to autocomplete sets
    $containerConfigurator->import(SetList::CODE_QUALITY);

    // register single rule
    $services = $containerConfigurator->services();
    $services->set(TypedPropertyRector::class);
};
```

Then dry run Rector:

```bash
vendor/bin/rector process src --dry-run
```

Rector will show you diff of files that it *would* change. To *make* the changes, drop `--dry-run`:

```bash
vendor/bin/rector process src
```

*Note: `rector.php` is loaded by default. For different location, use `--config` option.*

<br>

## Configuration

```php
// rector.php
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    // paths to refactor; solid alternative to CLI arguments
    $parameters->set(Option::PATHS, [__DIR__ . '/src', __DIR__ . '/tests']);

    // is your PHP version different from the one your refactor to? [default: your PHP version], uses PHP_VERSION_ID format
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_72);

    // Run Rector only on changed files
    $parameters->set(Option::ENABLE_CACHE, true);

    // Path to phpstan with extensions, that PHPSTan in Rector uses to determine types
    $parameters->set(Option::PHPSTAN_FOR_RECTOR_PATH, getcwd() . '/phpstan-for-config.neon');
};
```

<br>

## How to Contribute

See [the contribution guide](/CONTRIBUTING.md).

<br>

## Debugging

You can use `--debug` option, that will print nested exceptions output:

```bash
vendor/bin/rector process src/Controller --dry-run --debug
```

Or with Xdebug:

1. Make sure [Xdebug](https://xdebug.org/) is installed and configured
2. Add `--xdebug` option when running Rector

```bash
vendor/bin/rector process src/Controller --dry-run --xdebug
```

To assist with simple debugging Rector provides a 2 helpers to pretty-print AST-nodes:

```php
use PhpParser\Node\Scalar\String_;

$node = new String_('hello world!');

// prints node to string, as PHP code displays it
print_node($node);

// dump nested node object with nested properties
dump_node($node);
// 2nd argument is how deep the nesting is - this makes sure the dump is short and useful
dump_node($node, 1);
```

<br>

## Known Drawbacks

### How to Apply Coding Standards?

Rector uses [nikic/php-parser](https://github.com/nikic/PHP-Parser/), built on technology called an *abstract syntax tree* (AST). An AST doesn't know about spaces and when written to a file it produces poorly formatted code in both PHP and docblock annotations. **That's why your project needs to have a coding standard tool** and a set of formatting rules, so it can make Rector's output code nice and shiny again.

We're using [ECS](https://github.com/symplify/easy-coding-standard) with [this setup](ecs.php).
