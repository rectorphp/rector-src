# CLAUDE.md

Project-specific conventions for `rector/rector-src`. See `CONTRIBUTING.md` for the human-facing version.

## Project

- PHP `^8.3` required. Do not use syntax that breaks on 8.3.
- The package is `rector/rector-src`; it `replace`s `rector/rector`.
- Sibling extension packages (`rector-doctrine`, `rector-symfony`, `rector-phpunit`, `rector-downgrade-php`) are pulled in as `dev-main`.

## Layout

- `src/` — core engine (`Rector\` namespace).
- `rules/` — built-in Rector rules, also under `Rector\` namespace (PSR-4 maps both `src/` and `rules/` to `Rector\`).
- `rules-tests/` — tests for `rules/`, namespace `Rector\Tests\`.
- `tests/` — tests for `src/`, same `Rector\Tests\` namespace.
- `utils/` — internal dev tooling (`Rector\Utils\`).
- `config/` — config sets/presets (kept as plain class-string literals; do not let Rector rewrite them).
- `build/target-repository/docs` — documentation lives here, not in repo root.

## Coding style

- `declare(strict_types=1);` at the top of every PHP file.
- Classes are `final` by default; `abstract` only when explicitly intended for extension.
- Constructor property promotion with `private readonly` for dependencies.
- Run `composer fix-cs` (ECS) before committing; the ruleset is symplify + common + psr12.
- Do not add `@author`, `@since`, or change/`@var` tags that ECS would strip.
- No emojis in source.

## Quality gates

Match what `composer complete-check` runs:

```bash
composer check-cs      # ECS, read-only
composer phpstan       # PHPStan level 8, 512M
vendor/bin/phpunit
```

PHPStan extras enabled in `phpstan.neon`:
- `type-perfect`: `no_mixed`, `null_over_false`, `narrow_param`, `narrow_return` — return/param types must be narrow; prefer `null` over `false` for "no result".
- `unused-public`: public methods/properties/constants must be used somewhere. If you add a new public API, expect to use it or mark it accordingly.
- `symplify/phpstan-rules` + `rector-rules`: forbids `var_dump`, `dd`, `property_exists`, `class_exists`, `@` error suppression, dynamic names, etc., outside the narrowly listed exceptions in `phpstan.neon`. Do not add new exceptions casually — fix the code.

Rector applies to its own source: `composer rector` runs the config in `rector.php`.

## Writing a Rector rule

Required shape (see `rules/Php85/Rector/FuncCall/OrdSingleByteRector.php` as a canonical example):

1. Namespace mirrors the path: `Rector\<Category>\Rector\<NodeType>\<RuleName>`.
2. `final class` extends `Rector\Rector\AbstractRector`.
3. Implement `MinPhpVersionInterface` when the rule targets a specific PHP version; return a `PhpVersionFeature::*` constant from `provideMinPhpVersion()`.
4. Implement three methods:
   - `getRuleDefinition(): RuleDefinition` — one-line description + at least one `CodeSample` (before/after).
   - `getNodeTypes(): array` — list of `PhpParser\Node\...` classes to subscribe to.
   - `refactor(Node $node): ?Node` — return the new node, `null` for no change, or `NodeVisitor::REMOVE_NODE` to delete. Do **not** return integer values except `REMOVE_NODE` (see `rector.noIntegerRefactorReturn`).
5. Add a `@see` PHPDoc pointing to the test class: `@see \Rector\Tests\<...>\<RuleName>Test`.
6. Inject services via constructor promotion (`ValueResolver`, etc.); reuse what `AbstractRector` already exposes (`$this->nodeNameResolver`, `$this->nodeTypeResolver`, `$this->nodeFactory`, `$this->nodeComparator`).
7. Bail out early: check `isFirstClassCallable()`, name match, arg presence, type, **then** transform.

## Tests for a Rector rule

Mirror the rule path under `rules-tests/`:

```
rules-tests/<Category>/Rector/<NodeType>/<RuleName>/
├── <RuleName>Test.php
├── Fixture/
│   ├── some_case.php.inc
│   └── skip_some_case.php.inc
└── config/
    └── configured_rule.php
```

- Test class extends `Rector\Testing\PHPUnit\AbstractRectorTestCase`, uses `#[DataProvider('provideData')]`, and returns `self::yieldFilesFromDirectory(__DIR__ . '/Fixture')`.
- `provideConfigFilePath()` returns the config file that registers the rule and pins `phpVersion(PhpVersion::PHP_XX)` when version-bound.
- Fixtures use the `.php.inc` extension. Before/after are separated by a line containing exactly `-----`. A fixture with **no** `-----` separator asserts the file is unchanged; name those `skip_*.php.inc`.
- The fixture's `namespace` must match its directory.
- `Fixture/`, `Source/`, `Expected/` directories are auto-skipped by ECS, PHPStan, and Rector — don't try to make them conformant.

## What not to do

- Don't introduce new abstractions, traits, or helpers beyond what the task needs — the existing `AbstractRector` already exposes most node helpers.
- Don't modify `phpstan.neon` ignore lists or `ecs.php` skip lists to silence a new warning; fix the underlying code instead.
- Don't add `class_exists`/`property_exists`/`function_exists` runtime checks — use `ReflectionProvider` (errors from `symplify/phpstan-rules` will reject the PR).
- Don't bypass `instanceof` rules by adding a new ignore; only existing `Skipper`/internal paths are allowed.
- Don't touch files under `config/` with code-style rewrites — class strings there are intentional.
- Don't push docs into repo root — they belong under `build/target-repository/docs`.

## CI parity

`.github/workflows/code_analysis.yaml`, `tests.yaml`, `rector.yaml`, `e2e*.yaml`, and `phpstan_printer_test.yaml` mirror the local `composer complete-check`. If it passes locally with `composer complete-check && composer rector`, CI usually agrees.
