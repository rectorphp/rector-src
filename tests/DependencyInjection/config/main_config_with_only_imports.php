<?php

declare(strict_types=1);

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/first_config.php');
    $containerConfigurator->import(__DIR__ . '/second_config.php');
};
