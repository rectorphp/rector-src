# fast-phpunit (proof of concept)

A small Go orchestrator that runs the PHPUnit suite in parallel by splitting
test classes into **N balanced "warm" chunks**. Each worker boots PHP once and
runs many test classes in a single process, so the Rector container is built
`N` times instead of once per class.

## Why this is faster than "a process per test"

The dominant cost of a single Rector test class is **bootstrap**, not the
assertions:

| Step | Time |
| --- | --- |
| Boot only (container build, 0 tests) | ~0.23s (fixed, per process) |
| One class, 27 fixtures (warm) | 0.92s → ~26ms per fixture |

Average class has ~7 fixtures, so bootstrap is **~56% of an average class's
run time**. Any runner that spawns a fresh process per test class pays that
0.23s boot 685 times.

## Benchmark (full suite, 685 classes / 5833 fixtures, 24-core host)

| Mode | Wall time |
| --- | --- |
| Serial `vendor/bin/phpunit` (one process) | **38.3s** |
| Naive parallel, process per class (`xargs -P`) | slower than serial on subsets — boot dominates |
| **`fast-phpunit -p 12`** | **~9s** |

**~4x faster than serial.** Speedup flattens past ~12 workers: the heaviest
chunk bounds wall time, and 20+ concurrent PHP processes start contending.

## Usage

```bash
cd utils-tests-runner && go build -o fast-phpunit .
cd ..
utils-tests-runner/fast-phpunit -p 12                 # whole suite
utils-tests-runner/fast-phpunit -p 8 rules-tests/CodeQuality   # a subtree
```

Flags: `-p` workers (default = CPU count), `-bin` phpunit path.

## How isolation is handled

Running many classes in one process surfaces two kinds of shared state. Both
are handled so any chunking is correct:

1. **Shared temp cache (cross-process).** Rector caches parsed files under
   `sys_get_temp_dir()/rector_cached_files`; the fixture dumper also uses the
   system temp dir. Parallel processes racing that one directory throw
   `Failed to open directory` / `Directory not empty`. The runner gives each
   worker its own `TMPDIR`, so caches never collide.

2. **Leaked `phpVersion()` (in-process).** `phpVersion(...)` in a test config
   is stored in the static `SimpleParameterProvider` and was never reset
   between classes. A version-bound class (e.g. PHP 8.1) leaked its version
   into the next class in the same process, so a version-less class saw 8.1
   instead of the test default (`PhpVersion::PHP_10`) and produced wrong
   output. This is a latent, order-dependent bug — the serial suite only
   passes because of its class ordering; any reshuffle (this tool **or**
   paratest) can trigger it. Fixed in `AbstractRectorTestCase::tearDownAfterClass()`
   by resetting `PHP_VERSION_FEATURES` to the test default.

## Status

Proof of concept. Ships as an external helper; it shells out to the existing
`vendor/bin/phpunit`, so it does not change how tests are written or run in CI.
