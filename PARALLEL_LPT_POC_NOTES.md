# PoC: LPT bucket scheduling for parallel run

Branch: `poc/parallel-lpt-buckets` (local only, **not committed** — no tests yet)

Opt-in flag: `--experimental-runner`. Without it nothing changes.

Source: https://github.com/rectorphp/rector-src/issues/8494#issuecomment-5664864017 (@samsonasik)

## What samsonasik proposed

1. LPT (Longest Processing Time first): sort files largest-first by byte size, assign each file
   to the worker with the smallest total byte load so far.
2. One worker per bucket, started once.
3. Poll workers until no pending data. No timeout tweak, no job size tweak, no repeated
   close-open of workers.

Reference: `boundwize/structarmed` `src/Analyser/Parallel/ParallelAnalysisNodeExtractor.php`.

## What was actually implemented here

Faithful to 1 + 3, with one deliberate deviation on 2.

| | StructArmed | this PoC |
| --- | --- | --- |
| bucketing | LPT by filesize | LPT by filesize (same greedy) |
| assignment | static, 1 bucket per worker | static, 1 bucket per worker |
| dispatch | whole bucket in one shot | bucket sub-chunked into 16-file jobs, **only to its own worker** |
| worker lifetime | one process per bucket, never respawned | one process per bucket, never respawned |
| transport | `proc_open` + stdout polling | unchanged (ReactPHP + NDJSON over TCP) |

### Why the bucket is still sub-chunked

Rector's worker reports back once per job, and that single message does two things:

- advances the progress bar (`Bridge::FILES_COUNT`),
- cancels/re-arms the per-job timeout (`ParallelProcess::request()`).

A true one-shot bucket would mean: progress bar frozen at 0 % until a worker finishes its whole
bucket, and `parallelJobTimeout` (default 120 s) applied to the *entire* bucket — i.e. guaranteed
`Child process timed out after 120 seconds` on any non-trivial project. That is precisely the
failure mode reported in #8494.

Sub-chunking inside a statically-assigned bucket keeps the heartbeat while preserving the actual
win: **the worker process is never recycled**, so its warm in-memory caches (PHPStan reflection,
parsed AST, node scope resolver) survive across the whole bucket.

Round-trip cost of sub-chunking: ~820 extra NDJSON round-trips on a 13 k file project spread over
16 workers — sub-second, negligible next to a container boot.

### The other half of the change: no forced respawn

`ParallelFileProcessor::MAX_CHUNKS_PER_WORKER = 8` kills a worker after 8 chunks and spawns a fresh
one. At `jobSize: 16` that is a full container boot + cold caches every 128 files. On 13 111 files /
16 workers that is ~100 respawns. Strong suspicion this — not the chunk round-trips — is what makes
`jobSize: 16` (218 s) three times slower than `jobSize: 300` (75 s) in the issue's table.

In bucket mode the respawn is disabled entirely. **This is the main thing to benchmark and the main
risk** (see below).

## Files touched

New, all isolated under `src/Parallel/Experimental/`:

- `LptScheduleFactory.php` — LPT bucketing, heartbeat sub-chunking, worker-count math
- `ExperimentalParallelFileProcessor.php` — static bucket→worker binding, no respawn
- `ValueObject/BucketSchedule.php`

Existing files, minimal edits only:

- `src/Configuration/Option.php` — `EXPERIMENTAL_RUNNER` constant
- `src/Console/ProcessConfigureDecorator.php` — registers `--experimental-runner`
- `src/Application/ApplicationFileProcessor.php` — two constructor deps + one branch in `runParallel()`

Nothing on the default path changed, so a run without the flag is byte-identical to `main`.

## Measurements

Machine: **Apple M3 Max, 10 performance + 4 efficiency cores** (`hw.ncpu` = 14, which is what Rector
uses as the worker count). Corpus `vendor/`, 5 746 files.
`--dry-run --clear-cache --no-progress-bar --output-format=json`, runs strictly sequential.

| scheduler | workers | wall | user | sys | peak RSS | busy cores |
| --- | --: | --: | --: | --: | --: | --: |
| legacy | 14 | 152.2 s | 1043.8 s | 52.7 s | 751 MB | 7.2 |
| legacy | 10 | 164.3 s | 1026.9 s | 37.1 s | 691 MB | 6.5 |
| LPT, static buckets | 14 | 127.8 s | 1014.3 s | 39.6 s | 938 MB | 8.2 |
| LPT, static buckets | 10 | 153.8 s | 961.6 s | 31.9 s | 1256 MB | 6.5 |
| LPT + work stealing | 14 | 122.1 s | 1089.2 s | 52.3 s | 902 MB | 9.3 |
| LPT + work stealing | 12 | 113.6 s | 999.1 s | 33.3 s | 1075 MB | 9.1 |
| LPT + work stealing | 10 | **113.2 s** | 930.6 s | 31.5 s | 1156 MB | 8.5 |

All seven runs produce **3 338 identical diffs** (same sha over the file-sorted diff list) and the
same 2 errors. The scheduler does not change the outcome.

"busy cores" = `(user + sys) / real`, i.e. how many of the 14 were actually kept busy.

Headline: best legacy 152.2 s → best experimental 113.2 s, **-26 %**. At equal worker count (both on
the default 14) it is 152.2 s → 122.1 s, **-20 %**.

### Work stealing is the load-bearing part, not LPT

At 10 workers — all of them on performance cores, so the hardware is homogeneous — static buckets
give 153.8 s and stealing gives 113.2 s. That is **-26 % from stealing alone**, on hardware where
static assignment had no excuse.

So the E-core theory is at best a secondary effect. The primary one is that **byte size is a bad
predictor of processing cost**: a bucket balanced perfectly by bytes still finishes at wildly
different times. LPT bucketing on its own (samsonasik's proposal as written) lands at 153.8 s vs
legacy's 164.3 s at the same worker count — a real but modest gain. Nearly all of the win comes from
keeping the workers alive and letting idle ones take over work.

This also sharpens item 1 in "what is left": a timing-based cost model would make the initial
buckets right, but stealing is what makes the schedule robust when the model is wrong. Both, not
either.

### Worker count interacts with the scheduler — do not tune it standalone

Dropping from 14 to 10 workers **helps** the bucket scheduler (122.1 → 113.2) and **hurts** legacy
(152.2 → 164.3). There is no worker count that is right independent of how work is handed out, so
`CpuCoreCountProvider` returning logical cores is not a standalone bug to fix — it only looks like
one under the bucket scheduler.

`user` time also drops as workers drop (1089 → 931 under stealing). Careful with that number: work
done on an efficiency core accumulates more CPU-seconds for the same result, so part of the "extra
CPU" at 14 workers is just E-cores being slow, not waste.

### Memory is the consistent cost

Peak RSS is higher in every experimental run: 691-751 MB legacy vs 902-1256 MB experimental, worst
case **+82 %** (1256 MB vs 691 MB at 10 workers). It gets worse as workers get fewer, because each
worker then holds more files and nothing ever releases. This is the direct price of removing the
respawn and the main reason a memory-driven recycle is worth building (see below).

Control run on `rules/` (802 files, ~57 per worker): 6.17 s legacy vs 6.67 s experimental — pure
noise. At that size a worker never reaches the `MAX_CHUNKS_PER_WORKER × jobSize = 128` respawn
threshold, so both schedulers do exactly the same thing. **The gain appears precisely where the
respawns do**, which supports reading #8494's table as respawn churn rather than round-trip overhead.

sellero at ~13 k files sits far past that threshold.

### Why work stealing matters here

Static LPT assumes every worker is equally fast. On a big.LITTLE CPU it is not: 4 of the 14 workers
land on efficiency cores that are several times slower, so their bucket finishes long after the rest
and the run waits for them. Legacy's shared job pool corrected for that automatically; static
bucketing throws that correction away. Stealing puts it back while keeping the no-respawn win —
hence it beats both.

Consequence for benchmarking: **the split between static and stealing depends on the hardware.** On a
homogeneous CI runner static buckets should lose much less. Worth measuring on sellero's CI too, not
only on a laptop. `RECTOR_EXPERIMENTAL_NO_STEAL=1` turns stealing off for that A/B (temporary knob).

### What is left on the table

~9.3 of 14 cores busy means roughly a third of the machine is still idle. Ranked by expected value:

1. **Memory-driven worker recycle.** Not a speed item — the one that decides whether this is shippable
   at all. Report `memory_get_peak_usage()` in the result payload and recycle a worker above a
   threshold, handing its remaining chunks to the replacement. Cheap with sub-chunked buckets,
   impossible with true one-shot buckets.
2. **Cost model.** Byte size mispredicts badly (see above). Rector already writes a per-file cache
   entry; persisting the measured processing time there and using it as the LPT weight on the next
   run would make the first assignment right, with byte size as the cold-start fallback. Stealing
   already absorbs most of the error, so measure before assuming this is worth much on top.
3. **Steal policy.** Currently: take the largest remaining chunk from whoever has the most left.
   Untuned. Stealing the *smallest* chunk near the end of a run avoids creating a fresh straggler;
   a smaller chunk size for the tail would do the same. Given stealing is worth 26 %, its policy is
   worth an experiment.
4. **Cache write contention.** `sys` is 32-53 s, up to ~0.4 cores of kernel time; Rector writes one
   cache file per processed file, from every worker into one directory. Batching those writes per
   chunk is outside the scheduler and may be worth more than further scheduling work.
5. **Round-trip idle.** A worker sits idle between finishing a chunk and being handed the next one by
   the single-threaded main loop. Cheap fix: a one-chunk prefetch buffer. Bounded upside — `--no-diffs`
   and full-JSON runs differ little, so the main loop is not saturated.
6. **Worker boot.** 14 container boots, concurrent, ~1 s out of 113. Not worth chasing.

## How to A/B benchmark on sellero

The flag is opt-in, so baseline and experiment differ by one argument — no branch switching:

```bash
vendor/bin/rector process --dry-run --clear-cache --no-progress-bar
```

```bash
vendor/bin/rector process --dry-run --clear-cache --no-progress-bar --experimental-runner
```

Run them sequentially, never in parallel, and keep `--clear-cache` on both (or off on both).

Suggested matrix:

| run | flag | `jobSize` |
| --- | --- | --- |
| baseline | — | current |
| baseline tuned | — | 150 |
| experimental | `--experimental-runner` | 16 |
| experimental, fewer workers | `--experimental-runner` | 100 |

`jobSize` keeps its old meaning under the flag — it is the number of files that makes a worker worth
starting, so it still controls the worker count; it no longer controls how work is split.

Watch, besides wall time:

- peak RSS (`/usr/bin/time -l` on macOS reports the largest single child) — removing the respawn is a
  memory trade
- any `Child process timed out after N seconds`
- CPU % — a higher number at equal user time means better balance
- diffs identical across runs (`--output-format=json`, sort by file, compare) — the correctness gate

## Open risks / what is worth testing later

Nothing below is covered by a test yet — the user asked for a PoC first.

### Correctness

- [ ] `ScheduleFactory`: every input file lands in exactly one bucket, exactly once (no loss, no dupes).
      Easy to get wrong with the greedy loop.
- [ ] `ScheduleFactory`: duplicate file paths in the input list must not collapse (that is why the
      implementation sorts a list of tuples rather than using paths as array keys).
- [ ] `ScheduleFactory`: numeric-looking paths (e.g. `/tmp/123`) must not be cast to int array keys.
- [ ] `ScheduleFactory`: `$files === []` and `count($files) < $numberOfProcesses` → no empty buckets
      handed to `ParallelFileProcessor` (an empty job would trip `Assert::notEmpty()` in `WorkerCommand`).
- [ ] `ScheduleFactory`: non-existent / unreadable file → size 0, no warning, no crash.
- [ ] worker count math is unchanged vs. legacy: `min(ceil(count/jobSize), cpuCores, maxProcesses)`.
- [ ] `ParallelFileProcessor`: a worker only ever receives files from its own bucket.
- [ ] `ParallelFileProcessor`: all buckets drain; the run does not hang when one bucket is empty.
- [ ] end-to-end: file diffs produced in bucket mode == legacy mode == non-parallel mode.

### Failure handling

- [ ] a worker that dies mid-bucket: its remaining sub-chunks are currently **lost** (legacy mode had
      the same hole, but respawn masked it). Decide: redistribute, or fail loudly.
- [ ] `SYSTEM_ERROR_LIMIT` / `quitAll()` path still terminates cleanly with static buckets.
- [ ] timeout still fires for a genuinely hung worker (heartbeat must not accidentally mask it).

### Memory (the big one)

- [ ] a worker now processes its whole bucket without recycling. On a 13 k file project that is
      ~800 files in one process vs. 128 today. Measure peak RSS; consider a memory-driven respawn
      (`memory_get_peak_usage()` reported in the result payload → recycle above a threshold and hand
      the remaining sub-chunks to the fresh worker). This is *easy* with sub-chunked buckets and
      *impossible* with true one-shot buckets — a point in favour of the deviation above.

### Scheduling quality

- [ ] byte size is a proxy for work, not work itself. A 2 kB file hitting an expensive rule can cost
      more than a 50 kB one. Worth measuring per-worker finish times: if the spread is wide, LPT on
      bytes is not enough and work-stealing (idle worker pulls from the fattest remaining bucket)
      should be added. Ordering largest-first already helps, since the tail is fine-grained.
- [ ] `filesize()` is called once per file in the main process (13 k stats). Measure; the file list
      may already be able to carry sizes.
- [ ] does LPT interact badly with `--max-changes` (early break inside `processFiles`)?

### Cleanup before this could become a PR

- [ ] drop the `RECTOR_PARALLEL_SCHEDULER` env var, or promote it to a real `Option`
- [ ] `MAX_CHUNKS_PER_WORKER` becomes dead in bucket mode — decide its fate
- [ ] revisit the `jobSize` default (#8494 argues 16 is too low); with warm workers the optimum
      likely moves again — re-measure before changing it
- [ ] `composer check-cs`, `composer phpstan`, `vendor/bin/phpunit`
