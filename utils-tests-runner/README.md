# fast-phpunit (proof of concept)

Run the PHPUnit suite **~4x faster** by splitting test classes across parallel
workers that each boot PHP **once** and run many classes in a single process.

## Speed — measured on this suite

Full suite: **685 test classes / 5833 fixtures**, 24-core host.

| Mode | Wall time | vs serial |
| --- | --- | --- |
| Serial `vendor/bin/phpunit` (1 process) | **38.3s** | 1.0x |
| `fast-phpunit -p 8` | ~11s | ~3.5x |
| `fast-phpunit -p 12` | **~9s** | **~4.2x** |
| `fast-phpunit -p 24` | ~9.8s | ~3.9x |

Subset — `rules-tests/CodeQuality` (86 classes / 771 fixtures):

| Mode | Wall time |
| --- | --- |
| Serial (1 process) | 5.71s |
| Process-per-class, `xargs -P8` | 8.55s (**slower than serial**) |
| `fast-phpunit -p 8` | **2.43s** |

Sweet spot is ~12 workers; more does not help, because the heaviest chunk
bounds wall time and 20+ concurrent PHP processes start contending.

## Why it is faster

Bootstrap dominates a Rector test class, not the assertions:

| Step | Time |
| --- | --- |
| Boot only (container build, 0 tests) | ~0.23s — fixed, per process |
| One class, 27 fixtures (warm) | 0.92s → ~26ms/fixture |

Average class has ~7 fixtures, so **bootstrap is ~56% of an average class's run
time**. Any runner that spawns a fresh process per class pays that 0.23s boot
685 times — which is why process-per-class parallelism is slower than serial
(see subset table).

This runner splits classes into N chunks balanced by fixture count, and each
worker runs its whole chunk in one warm process — so the container is built N
times, not 685 times.

## Usage

```bash
cd utils-tests-runner && go build -o fast-phpunit .
cd ..
utils-tests-runner/fast-phpunit -p 12                          # whole suite
utils-tests-runner/fast-phpunit -p 8 rules-tests/CodeQuality   # a subtree
```

Flags: `-p` workers (default = CPU count), `-bin` phpunit path.

## Isolation — required to make it correct

Two shared-state issues surface when many classes share a process; both are
handled so any chunking is safe.

1. **Shared temp cache (cross-process).** Rector caches parsed files under
   `sys_get_temp_dir()/rector_cached_files`; parallel processes racing that
   directory throw `Failed to open directory` / `Directory not empty`. Each
   worker gets its own `TMPDIR`.

2. **Leaked `phpVersion()` (in-process) — a latent bug, fixed here.**
   `phpVersion(...)` is stored in the static `SimpleParameterProvider` and was
   never reset between classes, so a version-bound class leaks its version into
   the next class in the same process — a version-less class then sees, e.g.,
   PHP 8.1 instead of the test default (`PhpVersion::PHP_10`) and produces wrong
   output. The serial suite passes only because of its class ordering; any
   reshuffle (this tool **or** paratest) can trigger it. Fixed in
   `AbstractRectorTestCase::tearDownAfterClass()` by resetting
   `PHP_VERSION_FEATURES` to the test default.

## Status

Proof of concept. Standalone Go helper that shells out to the existing
`vendor/bin/phpunit`, so it does not change how tests are written or how CI
runs. Pure Go, no `.php`, so it is invisible to ECS / PHPStan / Rector.
