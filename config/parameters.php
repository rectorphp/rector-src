<?php

declare(strict_types=1);

use OndraM\CiDetector\CiDetector;

use Rector\Caching\ValueObject\Storage\MemoryCacheStorage;
use Rector\Config\RectorConfig;
use Rector\Core\Configuration\Option;

return static function (RectorConfig $rectorConfig): void {
    $parameters = $rectorConfig->parameters();

    // paths and extensions
    $parameters->set(Option::PATHS, []);
    $parameters->set(Option::FILE_EXTENSIONS, ['php']);
    $parameters->set(Option::AUTOLOAD_PATHS, []);

    // these files will be executed, useful e.g. for constant definitions
    $parameters->set(Option::BOOTSTRAP_FILES, []);

    // parallel
    $parameters->set(Option::PARALLEL, false);
    $parameters->set(Option::PARALLEL_MAX_NUMBER_OF_PROCESSES, 16);
    $parameters->set(Option::PARALLEL_JOB_SIZE, 20);
    $parameters->set(Option::PARALLEL_TIMEOUT_IN_SECONDS, 120);

    // FQN class importing
    $parameters->set(Option::AUTO_IMPORT_NAMES, false);
    $parameters->set(Option::IMPORT_SHORT_CLASSES, true);

    $parameters->set(Option::NESTED_CHAIN_METHOD_CALL_LIMIT, 60);
    $parameters->set(Option::SKIP, []);

    $parameters->set(Option::PHPSTAN_FOR_RECTOR_PATH, null);

    // cache
    $parameters->set(Option::CACHE_DIR, sys_get_temp_dir() . '/rector_cached_files');

    // use faster in-memory cache in CI.
    // CI always starts from scratch, therefore IO intensive caching is not worth it
    $ciDetector = new CiDetector();
    if ($ciDetector->isCiDetected()) {
        $parameters->set(Option::CACHE_CLASS, MemoryCacheStorage::class);
    }
};
