<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Bootstrap\ExtensionConfigResolver;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/services.php');
    $rectorConfig->import(__DIR__ . '/services-rules.php');
    $rectorConfig->import(__DIR__ . '/services-packages.php');
    $rectorConfig->import(__DIR__ . '/parameters.php');

    $extensionConfigResolver = new ExtensionConfigResolver();
    $extensionConfigFiles = $extensionConfigResolver->provide();
    foreach ($extensionConfigFiles as $extensionConfigFile) {
        $rectorConfig->import($extensionConfigFile->getRealPath());
    }

    // require only in dev
    $rectorConfig->import(__DIR__ . '/../utils/compiler/config/config.php', null, 'not_found');
};
