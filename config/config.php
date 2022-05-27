<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Bootstrap\ExtensionConfigResolver;
use Rector\Core\Configuration\Option;
use Symplify\EasyParallel\ValueObject\EasyParallelConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/services.php');
    $rectorConfig->import(__DIR__ . '/services-rules.php');
    $rectorConfig->import(__DIR__ . '/services-packages.php');

    // make use of https://github.com/symplify/easy-parallel
    $rectorConfig->import(EasyParallelConfig::FILE_PATH);

    // paths and extensions
    $rectorConfig->paths([]);

    $parameters = $rectorConfig->parameters();
    $parameters->set(Option::FILE_EXTENSIONS, ['php']);

    $rectorConfig->autoloadPaths([]);

    // these files will be executed, useful e.g. for constant definitions
    $rectorConfig->bootstrapFiles([]);

    // parallel
    $rectorConfig->disableParallel();

    $rectorConfig->parallel(seconds: 120, maxNumberOfProcess: 16, jobSize: 20);

    // FQN class importing
    $rectorConfig->disableImportNames();
    $rectorConfig->importShortClasses();

    $parameters->set(Option::NESTED_CHAIN_METHOD_CALL_LIMIT, 60);

    $rectorConfig->skip([]);

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
