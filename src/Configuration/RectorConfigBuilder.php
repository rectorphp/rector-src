<?php

declare(strict_types=1);

namespace Rector\Configuration;

use Rector\Caching\Contract\ValueObject\Storage\CacheStorageInterface;
use Rector\Config\RectorConfig;
use Rector\Contract\Rector\RectorInterface;
use Symfony\Component\Finder\Finder;

/**
 * @api
 */
final class RectorConfigBuilder
{
    /**
     * @var string[]
     */
    private array $paths = [];

    /**
     * @var string[]
     */
    private array $sets = [];

    /**
     * @var array<mixed>
     */
    private array $skip = [];

    /**
     * @var array<class-string<RectorInterface>>
     */
    private array $rules = [];

    /**
     * @var array<class-string<RectorInterface>, mixed[]>
     */
    private array $rulesWithConfiguration = [];

    /**
     * @var string[]
     */
    private array $fileExtensions = [];

    /**
     * @var null|class-string<CacheStorageInterface>
     */
    private ?string $cacheClass = null;

    private ?string $cacheDirectory = null;

    private ?string $containerCacheDirectory = null;

    /**
     * Enabled by default
     */
    private bool $parallel = true;

    private int $parallelTimeoutSeconds = 120;

    private int $parallelMaxNumberOfProcess = 16;

    private int $parallelJobSize = 16;

    private bool $importNames = false;

    private bool $importDocBlockNames = false;

    private bool $importShortClasses = true;

    private bool $removeUnusedImports = false;

    private bool $noDiffs = false;

    private ?string $memoryLimit = null;

    /**
     * @var string[]
     */
    private array $autoloadPaths = [];

    /**
     * @var string[]
     */
    private array $bootstrapFiles = [];

    private string $indentChar = ' ';

    private int $indentSize = 4;

    private ?string $phpstanConfig = null;

    /**
     * @var string[]
     */
    private array $phpstanConfigs = [];

    public function __invoke(RectorConfig $rectorConfig): void
    {
        $rectorConfig->sets($this->sets);
        $rectorConfig->paths($this->paths);
        $rectorConfig->skip($this->skip);
        $rectorConfig->rules($this->rules);

        foreach ($this->rulesWithConfiguration as $ruleWithConfiguration) {
            $rectorConfig->ruleWithConfiguration($ruleWithConfiguration[0], $ruleWithConfiguration[1]);
        }

        if ($this->fileExtensions !== []) {
            $rectorConfig->fileExtensions($this->fileExtensions);
        }

        if ($this->cacheClass !== null) {
            $rectorConfig->cacheClass($this->cacheClass);
        }

        if ($this->cacheDirectory !== null) {
            $rectorConfig->cacheDirectory($this->cacheDirectory);
        }

        if ($this->containerCacheDirectory !== null) {
            $rectorConfig->containerCacheDirectory($this->containerCacheDirectory);
        }

        if ($this->importNames || $this->importDocBlockNames) {
            $rectorConfig->importNames($this->importNames, $this->importDocBlockNames);
            $rectorConfig->importShortClasses($this->importShortClasses);
        }

        if ($this->removeUnusedImports) {
            $rectorConfig->removeUnusedImports($this->removeUnusedImports);
        }

        if ($this->noDiffs) {
            $rectorConfig->noDiffs();
        }

        if ($this->memoryLimit !== null) {
            $rectorConfig->memoryLimit($this->memoryLimit);
        }

        if ($this->autoloadPaths !== []) {
            $rectorConfig->autoloadPaths($this->autoloadPaths);
        }

        if ($this->bootstrapFiles !== []) {
            $rectorConfig->bootstrapFiles($this->bootstrapFiles);
        }

        if ($this->indentChar !== ' ' || $this->indentSize !== 4) {
            $rectorConfig->indent($this->indentChar, $this->indentSize);
        }

        if ($this->phpstanConfig !== null) {
            $rectorConfig->phpstanConfig($this->phpstanConfig);
        }

        if ($this->phpstanConfigs !== []) {
            $rectorConfig->phpstanConfigs($this->phpstanConfigs);
        }

        if ($this->parallel) {
            $rectorConfig->parallel(
                processTimeout: $this->parallelTimeoutSeconds,
                maxNumberOfProcess: $this->parallelMaxNumberOfProcess,
                jobSize: $this->parallelJobSize
            );
        } else {
            $rectorConfig->disableParallel();
        }
    }

    /**
     * @param string[] $paths
     */
    public function withPaths(array $paths): self
    {
        $this->paths = array_merge($this->paths, $paths);

        return $this;
    }

    /**
     * @param array<mixed> $skip
     */
    public function withSkip(array $skip): self
    {
        $this->skip = $skip;

        return $this;
    }

    /**
     * Include PHP files from the root directory,
     * typically ecs.php, rector.php etc.
     */
    public function withRootFiles(): self
    {
        $rootPhpFilesFinder = (new Finder())->files()
            ->in(getcwd())
            ->depth(0)
            ->name('*.php');

        foreach ($rootPhpFilesFinder as $rootPhpFileFinder) {
            $this->paths[] = $rootPhpFileFinder->getRealPath();
        }

        return $this;
    }

    /**
     * @param string[] $sets
     */
    public function withSets(array $sets): self
    {
        $this->sets = $sets;

        return $this;
    }

    /**
     * @param array<class-string<RectorInterface>> $rules
     */
    public function withRules(array $rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @param string[] $fileExtensions
     */
    public function withFileExtensions(array $fileExtensions): self
    {
        $this->fileExtensions = $fileExtensions;

        return $this;
    }

    public function withCacheDirectory(string $cacheDirectory, ?string $containerCacheDirectory = null): self
    {
        $this->cacheDirectory = $cacheDirectory;
        $this->containerCacheDirectory = $containerCacheDirectory;

        return $this;
    }

    /**
     * @param class-string<CacheStorageInterface> $cacheClass
     */
    public function withClassCache(string $cacheClass): self
    {
        $this->cacheClass = $cacheClass;

        return $this;
    }

    /**
     * @param class-string<(RectorInterface)> $rectorClass
     * @param mixed[] $configuration
     */
    public function withConfiguredRule(string $rectorClass, array $configuration): self
    {
        $this->rulesWithConfiguration[$rectorClass] = $configuration;

        return $this;
    }

    public function withParallel(
        ?int $timeoutSeconds = null,
        ?int $maxNumberOfProcess = null,
        ?int $jobSize = null
    ): self {
        $this->parallel = true;

        if (is_int($timeoutSeconds)) {
            $this->parallelTimeoutSeconds = $timeoutSeconds;
        }

        if (is_int($maxNumberOfProcess)) {
            $this->parallelMaxNumberOfProcess = $maxNumberOfProcess;
        }

        if (is_int($jobSize)) {
            $this->parallelJobSize = $jobSize;
        }

        return $this;
    }

    public function withoutParallel(): self
    {
        $this->parallel = false;

        return $this;
    }

    public function withImportNames(bool $importNames = true, bool $importDocBlockNames = true): self
    {
        $this->importNames = $importNames;
        $this->importDocBlockNames = $importDocBlockNames;

        return $this;
    }

    public function withImporShortClasses(bool $importShortClasses = true): self
    {
        $this->importShortClasses = $importShortClasses;

        return $this;
    }

    public function withRemoveUnusedImports(bool $removeUnusedImports = false): self
    {
        $this->removeUnusedImports = $removeUnusedImports;

        return $this;
    }

    public function withNoDiffs(): self
    {
        $this->noDiffs = true;
        return $this;
    }

    public function withMemoryLimit(string $memoryLimit): self
    {
        $this->memoryLimit = $memoryLimit;
        return $this;
    }

    public function withIndent(string $indentChar = ' ', int $indentSize = 4): self
    {
        $this->indentChar = $indentChar;
        $this->indentSize = $indentSize;

        return $this;
    }

    /**
     * @param string[] $autoloadPaths
     */
    public function withAutoloadPaths(array $autoloadPaths): self
    {
        $this->autoloadPaths = $autoloadPaths;
        return $this;
    }

    /**
     * @param string[] $bootstrapFiles
     */
    public function withBootstrapFiles(array $bootstrapFiles): self
    {
        $this->bootstrapFiles = $bootstrapFiles;
        return $this;
    }

    public function withPHPStanConfig(string $phpstanConfig): self
    {
        $this->phpstanConfig = $phpstanConfig;
        return $this;
    }

    public function withPHPStanConfigs(array $phpstanConfigs): self
    {
        $this->phpstanConfigs = $phpstanConfigs;
        return $this;
    }
}
