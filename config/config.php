<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Bootstrap\ExtensionConfigResolver;
use Rector\Core\Configuration\Option;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/services.php');
    $rectorConfig->import(__DIR__ . '/services-rules.php');
    $rectorConfig->import(__DIR__ . '/services-packages.php');

    // paths and extensions
    $rectorConfig->paths([]);

    $parameters = $rectorConfig->parameters();
    $parameters->set(Option::FILE_EXTENSIONS, ['php']);

    $rectorConfig->autoloadPaths([]);

    // these files will be executed, useful e.g. for constant definitions
    $rectorConfig->bootstrapFiles([]);

    // parallel
    $rectorConfig->disableParallel();

    $parameters->set(Option::PARALLEL_MAX_NUMBER_OF_PROCESSES, 16);
    $parameters->set(Option::PARALLEL_JOB_SIZE, 20);
    $parameters->set(Option::PARALLEL_TIMEOUT_IN_SECONDS, 120);

    // FQN class importing
    $rectorConfig->disableImportNames();
    $rectorConfig->importShortClasses();

    $parameters->set(Option::NESTED_CHAIN_METHOD_CALL_LIMIT, 60);

    $rectorConfig->skip([]);

    $parameters->set(Option::PHPSTAN_FOR_RECTOR_PATH, null);

    // cache
    $parameters->set(Option::CACHE_DIR, sys_get_temp_dir() . '/rector_cached_files');

    // use faster in-memory cache in CI.
    // CI always starts from scratch, therefore IO intensive caching is not worth it
    $ciDetector = new OndraM\CiDetector\CiDetector();
    if ($ciDetector->isCiDetected()) {
        $parameters->set(Option::CACHE_CLASS, \Rector\Caching\ValueObject\Storage\MemoryCacheStorage::class);
    }

    $extensionConfigResolver = new ExtensionConfigResolver();
    $extensionConfigFiles = $extensionConfigResolver->provide();
    foreach ($extensionConfigFiles as $extensionConfigFile) {
        $rectorConfig->import($extensionConfigFile->getRealPath());
    }

    // require only in dev
    $rectorConfig->import(__DIR__ . '/../utils/compiler/config/config.php', null, 'not_found');
};
