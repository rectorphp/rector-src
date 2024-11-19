<?php

declare(strict_types=1);

namespace Rector\Configuration;

use Nette\Utils\FileSystem;
use Rector\Bridge\SetProviderCollector;
use Rector\Bridge\SetRectorsResolver;
use Rector\Caching\Contract\ValueObject\Storage\CacheStorageInterface;
use Rector\Composer\InstalledPackageResolver;
use Rector\Config\Level\CodeQualityLevel;
use Rector\Config\Level\DeadCodeLevel;
use Rector\Config\Level\TypeDeclarationLevel;
use Rector\Config\RectorConfig;
use Rector\Config\RegisteredService;
use Rector\Configuration\Levels\LevelRulesResolver;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Console\Notifier;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Contract\Rector\RectorInterface;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Exception\Configuration\InvalidConfigurationException;
use Rector\Php\PhpVersionResolver\ComposerJsonPhpVersionResolver;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\Enum\SetGroup;
use Rector\Set\SetManager;
use Rector\Set\ValueObject\DowngradeLevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\FOSRestSetList;
use Rector\Symfony\Set\JMSSetList;
use Rector\Symfony\Set\SensiolabsSetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\ValueObject\PhpVersion;
use Symfony\Component\Finder\Finder;
use Webmozart\Assert\Assert;

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
     * @var array<class-string<ConfigurableRectorInterface>, mixed[]>
     */
    private array $rulesWithConfigurations = [];

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

    private ?bool $parallel = null;

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

    /**
     * @var string[]
     */
    private array $phpstanConfigs = [];

    /**
     * @var null|PhpVersion::*
     */
    private ?int $phpVersion = null;

    private ?string $symfonyContainerXmlFile = null;

    private ?string $symfonyContainerPhpFile = null;

    /**
     * To make sure type declarations set and level are not duplicated,
     * as both contain same rules
     */
    private ?bool $isTypeCoverageLevelUsed = null;

    private ?bool $isDeadCodeLevelUsed = null;

    private ?bool $isCodeQualityLevelUsed = null;

    private ?bool $isFluentNewLine = null;

    /**
     * @var RegisteredService[]
     */
    private array $registerServices = [];

    /**
     * @var array<SetGroup::*>
     */
    private array $setGroups = [];

    private ?bool $reportingRealPath = null;

    /**
     * @var string[]
     */
    private array $groupLoadedSets = [];

    private ?string $editorUrl = null;

    /**
     * @api soon to be used
     */
    private ?bool $isWithPhpSetsUsed = null;

    private ?bool $isWithPhpLevelUsed = null;

    public function __invoke(RectorConfig $rectorConfig): void
    {
        if ($this->setGroups !== []) {
            $setManager = new SetManager(new SetProviderCollector(), new InstalledPackageResolver(getcwd()));
            $this->groupLoadedSets = $setManager->matchBySetGroups($this->setGroups);

            SimpleParameterProvider::addParameter(Option::COMPOSER_BASED_SETS, $this->groupLoadedSets);
        }

        // merge sets together
        $this->sets = array_merge($this->sets, $this->groupLoadedSets);

        $uniqueSets = array_unique($this->sets);

        if ($this->isWithPhpLevelUsed && $this->isWithPhpSetsUsed) {
            throw new InvalidConfigurationException(sprintf(
                'Your config uses "withPhp*()" and "withPhpLevel()" methods at the same time.%sPick one of them to avoid rule conflicts.',
                PHP_EOL
            ));
        }

        if (in_array(SetList::TYPE_DECLARATION, $uniqueSets, true) && $this->isTypeCoverageLevelUsed === true) {
            throw new InvalidConfigurationException(sprintf(
                'Your config already enables type declarations set.%sRemove "->withTypeCoverageLevel()" as it only duplicates it, or remove type declaration set.',
                PHP_EOL
            ));
        }

        if (in_array(SetList::DEAD_CODE, $uniqueSets, true) && $this->isDeadCodeLevelUsed === true) {
            throw new InvalidConfigurationException(sprintf(
                'Your config already enables dead code set.%sRemove "->withDeadCodeLevel()" as it only duplicates it, or remove dead code set.',
                PHP_EOL
            ));
        }

        if (in_array(SetList::CODE_QUALITY, $uniqueSets, true) && $this->isCodeQualityLevelUsed === true) {
            throw new InvalidConfigurationException(sprintf(
                'Your config already enables code quality set.%sRemove "->withCodeQualityLevel()" as it only duplicates it, or remove code quality set.',
                PHP_EOL
            ));
        }

        if ($uniqueSets !== []) {
            $rectorConfig->sets($uniqueSets);
        }

        if ($this->paths !== []) {
            $rectorConfig->paths($this->paths);
        }

        // must be in upper part, as these services might be used by rule registered bellow
        foreach ($this->registerServices as $registerService) {
            $rectorConfig->singleton($registerService->getClassName());

            if ($registerService->getAlias()) {
                $rectorConfig->alias($registerService->getClassName(), $registerService->getAlias());
            }

            if ($registerService->getTag()) {
                $rectorConfig->tag($registerService->getClassName(), $registerService->getTag());
            }
        }

        if ($this->skip !== []) {
            $rectorConfig->skip($this->skip);
        }

        if ($this->rules !== []) {
            $rectorConfig->rules($this->rules);
        }

        foreach ($this->rulesWithConfigurations as $rectorClass => $configurations) {
            foreach ($configurations as $configuration) {
                $rectorConfig->ruleWithConfiguration($rectorClass, $configuration);
            }
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

        if ($this->phpstanConfigs !== []) {
            $rectorConfig->phpstanConfigs($this->phpstanConfigs);
        }

        if ($this->phpVersion !== null) {
            $rectorConfig->phpVersion($this->phpVersion);
        }

        if ($this->parallel !== null) {
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

        if ($this->symfonyContainerXmlFile !== null) {
            $rectorConfig->symfonyContainerXml($this->symfonyContainerXmlFile);
        }

        if ($this->symfonyContainerPhpFile !== null) {
            $rectorConfig->symfonyContainerPhp($this->symfonyContainerPhpFile);
        }

        if ($this->isFluentNewLine !== null) {
            $rectorConfig->newLineOnFluentCall($this->isFluentNewLine);
        }

        if ($this->reportingRealPath !== null) {
            $rectorConfig->reportingRealPath($this->reportingRealPath);
        }

        if ($this->editorUrl !== null) {
            $rectorConfig->editorUrl($this->editorUrl);
        }
    }

    /**
     * @param string[] $paths
     */
    public function withPaths(array $paths): self
    {
        $this->paths = $paths;

        return $this;
    }

    /**
     * @param array<mixed> $skip
     */
    public function withSkip(array $skip): self
    {
        $this->skip = array_merge($this->skip, $skip);

        return $this;
    }

    public function withSkipPath(string $skipPath): self
    {
        if (! str_contains($skipPath, '*')) {
            Assert::fileExists($skipPath);
        }

        return $this->withSkip([$skipPath]);
    }

    /**
     * Include PHP files from the root directory,
     * typically ecs.php, rector.php etc.
     */
    public function withRootFiles(): self
    {
        $gitIgnoreContents = [];
        if (file_exists(getcwd() . '/.gitignore')) {
            $gitIgnoreContents = array_filter(
                iterator_to_array(FileSystem::readLines(getcwd() . '/.gitignore')),
                function (string $string): bool {
                    $string = trim($string);

                    // new line
                    if ($string === '') {
                        return false;
                    }

                    // comment
                    if (str_starts_with($string, '#')) {
                        return false;
                    }

                    // normalize
                    $string = ltrim($string, '/\\');

                    // files in deep directory, no need to be in lists
                    if (str_contains($string, '/') || str_contains($string, '\\')) {
                        return false;
                    }

                    // only files
                    return is_file($string);
                }
            );

            // make realpath collection
            $gitIgnoreContents = array_map(
                function (string $string): string {
                    // normalize
                    $string = ltrim($string, '/\\');

                    return realpath($string);
                },
                $gitIgnoreContents
            );
        }

        $rootPhpFilesFinder = (new Finder())->files()
            ->in(getcwd())
            ->depth(0)
            ->name('*.php');

        foreach ($rootPhpFilesFinder as $rootPhpFileFinder) {
            $path = $rootPhpFileFinder->getRealPath();

            if (in_array($path, $gitIgnoreContents, true)) {
                continue;
            }

            $this->paths[] = $path;
        }

        return $this;
    }

    /**
     * @param string[] $sets
     */
    public function withSets(array $sets): self
    {
        $this->sets = array_merge($this->sets, $sets);

        return $this;
    }

    /**
     * Upgrade your annotations to attributes
     */
    public function withAttributesSets(
        bool $symfony = false,
        bool $doctrine = false,
        bool $mongoDb = false,
        bool $gedmo = false,
        bool $phpunit = false,
        bool $fosRest = false,
        bool $jms = false,
        bool $sensiolabs = false,
        bool $all = false
    ): self {
        // if nothing is passed, enable all as convention in other method
        if (func_get_args() === []) {
            $all = true;
        }

        if ($symfony || $all) {
            $this->sets[] = SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES;
        }

        if ($doctrine || $all) {
            $this->sets[] = DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES;
        }

        if ($mongoDb || $all) {
            $this->sets[] = DoctrineSetList::MONGODB__ANNOTATIONS_TO_ATTRIBUTES;
        }

        if ($gedmo || $all) {
            $this->sets[] = DoctrineSetList::GEDMO_ANNOTATIONS_TO_ATTRIBUTES;
        }

        if ($phpunit || $all) {
            $this->sets[] = PHPUnitSetList::ANNOTATIONS_TO_ATTRIBUTES;
        }

        if ($fosRest || $all) {
            $this->sets[] = FOSRestSetList::ANNOTATIONS_TO_ATTRIBUTES;
        }

        if ($jms || $all) {
            $this->sets[] = JMSSetList::ANNOTATIONS_TO_ATTRIBUTES;
        }

        if ($sensiolabs || $all) {
            $this->sets[] = SensiolabsSetList::ANNOTATIONS_TO_ATTRIBUTES;
        }

        return $this;
    }

    /**
     * make use of polyfill packages in composer.json
     */
    public function withPhpPolyfill(): self
    {
        $this->sets[] = SetList::PHP_POLYFILLS;
        return $this;
    }

    /**
     * What PHP sets should be applied? By default the same version
     * as composer.json has is used
     */
    public function withPhpSets(
        bool $php83 = false,
        bool $php82 = false,
        bool $php81 = false,
        bool $php80 = false,
        bool $php74 = false,
        bool $php73 = false,
        bool $php72 = false,
        bool $php71 = false,
        bool $php70 = false,
        bool $php56 = false,
        bool $php55 = false,
        bool $php54 = false,
        bool $php53 = false,
        bool $php84 = false, // place on later as BC break when used in php 7.x without named arg
    ): self {
        $this->isWithPhpSetsUsed = true;
        $this->withPhpPolyfill();

        $pickedArguments = array_filter(func_get_args());
        if ($pickedArguments !== []) {
            Notifier::notifyWithPhpSetsNotSuitableForPHP80();
        }

        if (count($pickedArguments) > 1) {
            throw new InvalidConfigurationException(
                sprintf(
                    'Pick only one version target in "withPhpSets()". All rules up to this version will be used.%sTo use your composer.json PHP version, keep arguments empty.',
                    PHP_EOL
                )
            );
        }

        if ($pickedArguments === []) {
            $projectPhpVersion = ComposerJsonPhpVersionResolver::resolveFromCwdOrFail();
            $phpLevelSets = PhpLevelSetResolver::resolveFromPhpVersion($projectPhpVersion);

            $this->sets = array_merge($this->sets, $phpLevelSets);

            return $this;
        }

        if ($php53) {
            $this->withPhp53Sets();
            return $this;
        }

        if ($php54) {
            $this->withPhp54Sets();
            return $this;
        }

        if ($php55) {
            $this->withPhp55Sets();
            return $this;
        }

        if ($php56) {
            $this->withPhp56Sets();
            return $this;
        }

        if ($php70) {
            $this->withPhp70Sets();
            return $this;
        }

        if ($php71) {
            $this->withPhp71Sets();
            return $this;
        }

        if ($php72) {
            $this->withPhp72Sets();
            return $this;
        }

        if ($php73) {
            $this->withPhp73Sets();
            return $this;
        }

        if ($php74) {
            $this->withPhp74Sets();
            return $this;
        }

        if ($php80) {
            $targetPhpVersion = PhpVersion::PHP_80;
        } elseif ($php81) {
            $targetPhpVersion = PhpVersion::PHP_81;
        } elseif ($php82) {
            $targetPhpVersion = PhpVersion::PHP_82;
        } elseif ($php83) {
            $targetPhpVersion = PhpVersion::PHP_83;
        } elseif ($php84) {
            $targetPhpVersion = PhpVersion::PHP_84;
        } else {
            throw new InvalidConfigurationException('Invalid PHP version set');
        }

        $phpLevelSets = PhpLevelSetResolver::resolveFromPhpVersion($targetPhpVersion);
        $this->sets = array_merge($this->sets, $phpLevelSets);

        return $this;
    }

    /**
     * Following methods are suitable for PHP 7.4 and lower, before named args
     * Let's keep them without warning, in case Rector is run on both PHP 7.4 and PHP 8.0 in CI
     */
    public function withPhp53Sets(): self
    {
        $this->isWithPhpSetsUsed = true;

        $this->sets = array_merge($this->sets, PhpLevelSetResolver::resolveFromPhpVersion(PhpVersion::PHP_53));

        return $this;
    }

    public function withPhp54Sets(): self
    {
        $this->isWithPhpSetsUsed = true;

        $this->sets = array_merge($this->sets, PhpLevelSetResolver::resolveFromPhpVersion(PhpVersion::PHP_54));

        return $this;
    }

    public function withPhp55Sets(): self
    {
        $this->isWithPhpSetsUsed = true;

        $this->sets = array_merge($this->sets, PhpLevelSetResolver::resolveFromPhpVersion(PhpVersion::PHP_55));

        return $this;
    }

    public function withPhp56Sets(): self
    {
        $this->isWithPhpSetsUsed = true;

        $this->sets = array_merge($this->sets, PhpLevelSetResolver::resolveFromPhpVersion(PhpVersion::PHP_56));

        return $this;
    }

    public function withPhp70Sets(): self
    {
        $this->isWithPhpSetsUsed = true;

        $this->sets = array_merge($this->sets, PhpLevelSetResolver::resolveFromPhpVersion(PhpVersion::PHP_70));

        return $this;
    }

    public function withPhp71Sets(): self
    {
        $this->isWithPhpSetsUsed = true;

        $this->sets = array_merge($this->sets, PhpLevelSetResolver::resolveFromPhpVersion(PhpVersion::PHP_71));

        return $this;
    }

    public function withPhp72Sets(): self
    {
        $this->isWithPhpSetsUsed = true;

        $this->sets = array_merge($this->sets, PhpLevelSetResolver::resolveFromPhpVersion(PhpVersion::PHP_72));

        return $this;
    }

    public function withPhp73Sets(): self
    {
        $this->isWithPhpSetsUsed = true;

        $this->sets = array_merge($this->sets, PhpLevelSetResolver::resolveFromPhpVersion(PhpVersion::PHP_73));

        return $this;
    }

    public function withPhp74Sets(): self
    {
        $this->isWithPhpSetsUsed = true;

        $this->sets = array_merge($this->sets, PhpLevelSetResolver::resolveFromPhpVersion(PhpVersion::PHP_74));

        return $this;
    }

    // there is no withPhp80Sets() and above,
    // as we already use PHP 8.0 and should go with withPhpSets() instead

    public function withPreparedSets(
        bool $deadCode = false,
        bool $codeQuality = false,
        bool $codingStyle = false,
        bool $typeDeclarations = false,
        bool $privatization = false,
        bool $naming = false,
        bool $instanceOf = false,
        bool $earlyReturn = false,
        bool $strictBooleans = false,
        bool $carbon = false,
        bool $rectorPreset = false,
        bool $phpunitCodeQuality = false,
        bool $doctrineCodeQuality = false,
        bool $symfonyCodeQuality = false,
        bool $symfonyConfigs = false,
    ): self {
        Notifier::notifyNotSuitableMethodForPHP74(__METHOD__);

        $setMap = [
            SetList::DEAD_CODE => $deadCode,
            SetList::CODE_QUALITY => $codeQuality,
            SetList::CODING_STYLE => $codingStyle,
            SetList::TYPE_DECLARATION => $typeDeclarations,
            SetList::PRIVATIZATION => $privatization,
            SetList::NAMING => $naming,
            SetList::INSTANCEOF => $instanceOf,
            SetList::EARLY_RETURN => $earlyReturn,
            SetList::STRICT_BOOLEANS => $strictBooleans,
            SetList::CARBON => $carbon,
            SetList::RECTOR_PRESET => $rectorPreset,
            PHPUnitSetList::PHPUNIT_CODE_QUALITY => $phpunitCodeQuality,
            DoctrineSetList::DOCTRINE_CODE_QUALITY => $doctrineCodeQuality,
            SymfonySetList::SYMFONY_CODE_QUALITY => $symfonyCodeQuality,
            SymfonySetList::CONFIGS => $symfonyConfigs,
        ];

        foreach ($setMap as $setPath => $isEnabled) {
            if ($isEnabled) {
                $this->sets[] = $setPath;
            }
        }

        return $this;
    }

    public function withComposerBased(bool $twig = false, bool $doctrine = false, bool $phpunit = false): self
    {
        $setMap = [
            SetGroup::TWIG => $twig,
            SetGroup::DOCTRINE => $doctrine,
            SetGroup::PHPUNIT => $phpunit,
        ];

        foreach ($setMap as $setPath => $isEnabled) {
            if ($isEnabled) {
                $this->setGroups[] = $setPath;
            }
        }

        return $this;
    }

    /**
     * @param array<class-string<RectorInterface>> $rules
     */
    public function withRules(array $rules): self
    {
        $this->rules = array_merge($this->rules, $rules);

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

    /**
     * @param class-string<CacheStorageInterface>|null $cacheClass
     */
    public function withCache(
        ?string $cacheDirectory = null,
        ?string $cacheClass = null,
        ?string $containerCacheDirectory = null
    ): self {
        $this->cacheDirectory = $cacheDirectory;
        $this->cacheClass = $cacheClass;
        $this->containerCacheDirectory = $containerCacheDirectory;

        return $this;
    }

    /**
     * @param class-string<ConfigurableRectorInterface> $rectorClass
     * @param mixed[] $configuration
     */
    public function withConfiguredRule(string $rectorClass, array $configuration): self
    {
        $this->rulesWithConfigurations[$rectorClass][] = $configuration;

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

    public function withImportNames(
        bool $importNames = true,
        bool $importDocBlockNames = true,
        bool $importShortClasses = true,
        bool $removeUnusedImports = false
    ): self {
        $this->importNames = $importNames;
        $this->importDocBlockNames = $importDocBlockNames;
        $this->importShortClasses = $importShortClasses;
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

    /**
     * @param string[] $phpstanConfigs
     */
    public function withPHPStanConfigs(array $phpstanConfigs): self
    {
        $this->phpstanConfigs = $phpstanConfigs;
        return $this;
    }

    /**
     * @param PhpVersion::* $phpVersion
     */
    public function withPhpVersion(int $phpVersion): self
    {
        $this->phpVersion = $phpVersion;
        return $this;
    }

    public function withSymfonyContainerXml(string $symfonyContainerXmlFile): self
    {
        if (! str_ends_with($symfonyContainerXmlFile, '.xml')) {
            throw new InvalidConfigurationException(sprintf(
                'Provided dumped Symfony container must have "xml" suffix. "%s" given',
                $symfonyContainerXmlFile
            ));
        }

        $this->symfonyContainerXmlFile = $symfonyContainerXmlFile;
        return $this;
    }

    public function withSymfonyContainerPhp(string $symfonyContainerPhpFile): self
    {
        if (! str_ends_with($symfonyContainerPhpFile, '.php')) {
            throw new InvalidConfigurationException(sprintf(
                'Provided dumped Symfony container must have "php" suffix. "%s" given',
                $symfonyContainerPhpFile
            ));
        }

        $this->symfonyContainerPhpFile = $symfonyContainerPhpFile;
        return $this;
    }

    /**
     * @experimental since 0.19.7 Raise your dead-code coverage from the safest rules
     * to more affecting ones, one level at a time
     */
    public function withDeadCodeLevel(int $level): self
    {
        Assert::natural($level);

        $this->isDeadCodeLevelUsed = true;

        $levelRules = LevelRulesResolver::resolve($level, DeadCodeLevel::RULES, __METHOD__);

        $this->rules = array_merge($this->rules, $levelRules);

        return $this;
    }

    /**
     * @experimental since 0.19.7 Raise your type coverage from the safest type rules
     * to more affecting ones, one level at a time
     */
    public function withTypeCoverageLevel(int $level): self
    {
        Assert::natural($level);

        $this->isTypeCoverageLevelUsed = true;

        $levelRules = LevelRulesResolver::resolve($level, TypeDeclarationLevel::RULES, __METHOD__);

        $this->rules = array_merge($this->rules, $levelRules);

        return $this;
    }

    /**
     * @experimental Since 1.2.5 Raise your PHP level from, one level at a time
     */
    public function withPhpLevel(int $level): self
    {
        Assert::natural($level);

        $this->isWithPhpLevelUsed = true;

        $phpVersion = ComposerJsonPhpVersionResolver::resolveFromCwdOrFail();

        $setRectorsResolver = new SetRectorsResolver();
        $setFilePaths = PhpLevelSetResolver::resolveFromPhpVersion($phpVersion);

        $rectorRulesWithConfiguration = $setRectorsResolver->resolveFromFilePathsIncludingConfiguration($setFilePaths);

        foreach ($rectorRulesWithConfiguration as $position => $rectorRuleWithConfiguration) {
            // add rules untill level is reached
            if ($position > $level) {
                continue;
            }

            if (is_string($rectorRuleWithConfiguration)) {
                $this->rules[] = $rectorRuleWithConfiguration;
            } elseif (is_array($rectorRuleWithConfiguration)) {
                foreach ($rectorRuleWithConfiguration as $rectorRule => $rectorRuleConfiguration) {
                    /** @var class-string<ConfigurableRectorInterface> $rectorRule */
                    $this->withConfiguredRule($rectorRule, $rectorRuleConfiguration);
                }
            }
        }

        return $this;
    }

    /**
     * @experimental Raise your code quality from the safest rules
     * to more affecting ones, one level at a time
     */
    public function withCodeQualityLevel(int $level): self
    {
        Assert::natural($level);

        $this->isCodeQualityLevelUsed = true;

        $levelRules = LevelRulesResolver::resolve($level, CodeQualityLevel::RULES, __METHOD__);

        $this->rules = array_merge($this->rules, $levelRules);

        foreach (CodeQualityLevel::RULES_WITH_CONFIGURATION as $rectorClass => $configuration) {
            $this->rulesWithConfigurations[$rectorClass][] = $configuration;
        }

        return $this;
    }

    public function withFluentCallNewLine(bool $isFluentNewLine = true): self
    {
        $this->isFluentNewLine = $isFluentNewLine;
        return $this;
    }

    public function registerService(string $className, ?string $alias = null, ?string $tag = null): self
    {
        $this->registerServices[] = new RegisteredService($className, $alias, $tag);

        return $this;
    }

    public function withDowngradeSets(
        bool $php82 = false,
        bool $php81 = false,
        bool $php80 = false,
        bool $php74 = false,
        bool $php73 = false,
        bool $php72 = false,
        bool $php71 = false,
    ): self {
        $pickedArguments = array_filter(func_get_args());
        if (count($pickedArguments) !== 1) {
            throw new InvalidConfigurationException(
                'Pick only one PHP version target in "withDowngradeSets()". All rules down to this version will be used.',
            );
        }

        if ($php82) {
            $this->sets[] = DowngradeLevelSetList::DOWN_TO_PHP_82;
        }

        if ($php81) {
            $this->sets[] = DowngradeLevelSetList::DOWN_TO_PHP_81;
        }

        if ($php80) {
            $this->sets[] = DowngradeLevelSetList::DOWN_TO_PHP_80;
        }

        if ($php74) {
            $this->sets[] = DowngradeLevelSetList::DOWN_TO_PHP_74;
        }

        if ($php73) {
            $this->sets[] = DowngradeLevelSetList::DOWN_TO_PHP_73;
        }

        if ($php72) {
            $this->sets[] = DowngradeLevelSetList::DOWN_TO_PHP_72;
        }

        if ($php71) {
            $this->sets[] = DowngradeLevelSetList::DOWN_TO_PHP_71;
        }

        return $this;
    }

    public function withRealPathReporting(bool $absolutePath = true): self
    {
        $this->reportingRealPath = $absolutePath;

        return $this;
    }

    public function withEditorUrl(string $editorUrl): self
    {
        $this->editorUrl = $editorUrl;

        return $this;
    }
}
