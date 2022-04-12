<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassMethod\MakeInheritedMethodVisibilitySameAsParentRector;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersionFeature;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersionFeature::PARENT_VISIBILITY_OVERRIDE);

    $services = $containerConfigurator->services();
    $services->set(MakeInheritedMethodVisibilitySameAsParentRector::class);
};
