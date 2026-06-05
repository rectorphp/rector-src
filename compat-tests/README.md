# Rector Compatibility Tests

A small test harness that verifies [Rector](https://github.com/rectorphp/rector) keeps working alongside other tools in the PHP static-analysis ecosystem.

It runs a minimal real-world setup — a custom Rector rule, a custom PHPStan rule, and a PHPUnit test suite — against multiple combinations of dependencies in CI, to catch breakage early.

## What it checks

* A custom **Rector** rule (`MakeClassFinalRector`, `UseGetArgRector`) loads and applies correctly.
* A custom **PHPStan** rule (`CustomPHPStanRule`) loads and runs alongside Rector.
* **PHPUnit 10, 11, and 12** all work with Rector's preloaded dependencies.
* Manually including **`nikic/php-parser`** in user code does not conflict with the copy Rector ships.
* `rector-laravel` + `nikic/php-parser` upgrade scenarios run cleanly (see [rectorphp/rector#9470](https://github.com/rectorphp/rector/issues/9470)).

This project lives inside `rector/rector-src` (under `compat-tests/`) and pulls in `rector/rector:dev-main` as a downstream dependency, so the compat suite is maintained in one place. It mirrors the standalone [rectorphp/rector-compat-tests](https://github.com/rectorphp/rector-compat-tests).

## How it runs

GitHub Actions runs the matrix daily (`0 6 * * *`) and on every push/PR, from the repository root `.github/workflows`:

* `compat_test.yaml` — Rector dev + PHPUnit 10 / 11 / 12
* `rector_laravel_rector_dev.yaml` — Rector + rector-laravel + php-parser
