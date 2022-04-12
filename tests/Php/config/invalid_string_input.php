<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, '7.3');
};
