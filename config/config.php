<?php

declare(strict_types=1);

use Rector\Core\Bootstrap\ExtensionConfigResolver;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/services.php');
    $containerConfigurator->import(__DIR__ . '/services-rules.php');
    $containerConfigurator->import(__DIR__ . '/services-packages.php');
    $containerConfigurator->import(__DIR__ . '/parameters.php');

    $extensionConfigResolver = new ExtensionConfigResolver();
    $extensionConfigFiles = $extensionConfigResolver->provide();
    foreach ($extensionConfigFiles as $extensionConfigFile) {
        $containerConfigurator->import($extensionConfigFile->getRealPath());
    }

    // require only in dev
    $containerConfigurator->import(__DIR__ . '/../utils/compiler/config/config.php', null, 'not_found');
};
