<?php

declare(strict_types=1);

use Rector\Utils\RuleDocGenerator\Category\RectorCategoryInferer;
use Rector\Utils\RuleDocGenerator\PostRectorOutFilter;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $rectorConfig): void {
    $services = $rectorConfig->services();
    $services->set(RectorCategoryInferer::class);

    // remove PostRectorInterface rules from the generated output; they're internal and confusing for end-user
    $services->set(PostRectorOutFilter::class);
};
