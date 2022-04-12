<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersionFeature;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersionFeature::ARRAY_KEY_FIRST_LAST - 1);
};
