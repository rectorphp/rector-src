<?php

declare(strict_types=1);

namespace Rector\Configuration;

use Rector\Caching\Contract\ValueObject\Storage\CacheStorageInterface;
use Rector\Caching\ValueObject\Storage\FileCacheStorage;

final class Option
{
    public const string SOURCE = 'source';

    public const string AUTOLOAD_FILE = 'autoload-file';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::bootstrapFiles() instead
     * @var string
     */
    public const string BOOTSTRAP_FILES = 'bootstrap_files';

    public const string DRY_RUN = 'dry-run';

    public const string DRY_RUN_SHORT = 'n';

    public const string OUTPUT_FORMAT = 'output-format';

    public const string NO_PROGRESS_BAR = 'no-progress-bar';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::phpVersion() instead
     * @var string
     */
    public const string PHP_VERSION_FEATURES = 'php_version_features';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::importNames() instead
     * @var string
     */
    public const string AUTO_IMPORT_NAMES = 'auto_import_names';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::polyfillPackages() instead
     * @var string
     */
    public const string POLYFILL_PACKAGES = 'polyfill_packages';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::importNames() instead
     * @var string
     */
    public const string AUTO_IMPORT_DOC_BLOCK_NAMES = 'auto_import_doc_block_names';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::importShortClasses() instead
     * @var string
     */
    public const string IMPORT_SHORT_CLASSES = 'import_short_classes';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::symfonyContainerXml() instead
     * @var string
     */
    public const string SYMFONY_CONTAINER_XML_PATH_PARAMETER = 'symfony_container_xml_path';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::symfonyContainerPhp()
     * @var string
     */
    public const string SYMFONY_CONTAINER_PHP_PATH_PARAMETER = 'symfony_container_php_path';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::newLineOnFluentCall()
     * @var string
     */
    public const string NEW_LINE_ON_FLUENT_CALL = 'new_line_on_fluent_call';

    public const string CLEAR_CACHE = 'clear-cache';

    public const string ONLY = 'only';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::parallel() instead
     * @var string
     */
    public const string PARALLEL = 'parallel';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::paths() instead
     * @var string
     */
    public const string PATHS = 'paths';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::autoloadPaths() instead
     * @var string
     */
    public const string AUTOLOAD_PATHS = 'autoload_paths';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::skip() instead
     * @var string
     */
    public const string SKIP = 'skip';

    /**
     * @internal Use RectorConfig::fileExtensions() instead
     */
    public const string FILE_EXTENSIONS = 'file_extensions';

    /**
     * @internal Use RectorConfig::cacheDirectory() instead
     */
    public const string CACHE_DIR = 'cache_dir';

    /**
     * Cache backend. Most of the time we cache in files, but in ephemeral environment (e.g. CI), a faster `MemoryCacheStorage` can be useful.
     * @internal Use RectorConfig::cacheClass() instead
     *
     * @var class-string<CacheStorageInterface>
     * @internal
     */
    public const string CACHE_CLASS = FileCacheStorage::class;

    public const string DEBUG = 'debug';

    public const string XDEBUG = 'xdebug';

    public const string CONFIG = 'config';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::phpstanConfig() instead
     * @var string
     */
    public const string PHPSTAN_FOR_RECTOR_PATHS = 'phpstan_for_rector_paths';

    public const string NO_DIFFS = 'no-diffs';

    public const string AUTOLOAD_FILE_SHORT = 'a';

    public const string PARALLEL_IDENTIFIER = 'identifier';

    public const string PARALLEL_PORT = 'port';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::parallel() instead with pass int $jobSize parameter
     * @var string
     */
    public const string PARALLEL_JOB_SIZE = 'parallel-job-size';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::parallel() instead with pass int $maxNumberOfProcess parameter
     * @var string
     */
    public const string PARALLEL_MAX_NUMBER_OF_PROCESSES = 'parallel-max-number-of-processes';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::parallel() instead with pass int $seconds parameter
     * @var string
     */
    public const string PARALLEL_JOB_TIMEOUT_IN_SECONDS = 'parallel-job-timeout-in-seconds';

    public const string MEMORY_LIMIT = 'memory-limit';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::indent() method
     * @var string
     */
    public const string INDENT_CHAR = 'indent-char';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::indent() method
     * @var string
     */
    public const string INDENT_SIZE = 'indent-size';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::removeUnusedImports() method
     * @var string
     */
    public const string REMOVE_UNUSED_IMPORTS = 'remove-unused-imports';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::containerCacheDirectory() method
     * @var string
     */
    public const string CONTAINER_CACHE_DIRECTORY = 'container-cache-directory';

    /**
     * @internal For cache invalidation in case of change
     */
    public const string REGISTERED_RECTOR_RULES = 'registered_rector_rules';

    /**
     * @internal For cache invalidation in case of change
     */
    public const string REGISTERED_RECTOR_SETS = 'registered_rector_sets';

    /**
     * @internal For verify RectorConfigBuilder instance recreated
     */
    public const string IS_RECTORCONFIG_BUILDER_RECREATED = 'is_rectorconfig_builder_recreated';

    /**
     * @internal For verify skipped rules exists in registered rules
     */
    public const string SKIPPED_RECTOR_RULES = 'skipped_rector_rules';

    /**
     * @internal For collect skipped start with short open tag files to be reported
     */
    public const string SKIPPED_START_WITH_SHORT_OPEN_TAG_FILES = 'skipped_start_with_short_open_tag_files';

    /**
     * @internal For reporting with absolute paths instead of relative paths (default behaviour)
     * @see \Rector\Config\RectorConfig::reportingRealPath()
     */
    public const string ABSOLUTE_FILE_PATH = 'absolute_file_path';

    /**
     * @internal To add editor links to console output
     * @see \Rector\Config\RectorConfig::editorUrl()
     */
    public const string EDITOR_URL = 'editor_url';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::treatClassesAsFinal() method
     * @var string
     */
    public const string TREAT_CLASSES_AS_FINAL = 'treat_classes_as_final';

    /**
     * @internal Use @see \Rector\Config\RectorConfig::eagerlyResolveDeprecations() method
     * @var string
     */
    public const string EAGERLY_RESOLVE_DEPRECATIONS = 'eagerly_resolve_deprecations';

    /**
     * @internal To report composer based loaded sets
     * @see \Rector\Configuration\RectorConfigBuilder::withComposerBased()
     */
    public const string COMPOSER_BASED_SETS = 'composer_based_sets';

    /**
     * @internal To filter files by specific suffix
     */
    public const string ONLY_SUFFIX = 'only-suffix';

    /**
     * @internal To report overflow levels in ->with*Level() methods
     */
    public const string LEVEL_OVERFLOWS = 'level_overflows';

    /**
     * @internal To avoid registering rules via ->withRules(), that are already loaded in sets,
     * and keep rector.php clean
     */
    public const string ROOT_STANDALONE_REGISTERED_RULES = 'root_standalone_registered_rules';

    /**
     * @internal The other half of ROOT_STANDALONE_REGISTERED_RULES to compare
     */
    public const string SET_REGISTERED_RULES = 'set_registered_rules';

    /**
     * @internal to allow process file without extension if explicitly registered
     */
    public const string FILES_WITHOUT_EXTENSION = 'files_without_extension';
}
