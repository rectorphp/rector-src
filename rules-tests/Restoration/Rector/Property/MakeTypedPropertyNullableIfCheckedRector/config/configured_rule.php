<?php

declare(strict_types=1);

use Rector\Restoration\Rector\Property\MakeTypedPropertyNullableIfCheckedRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(MakeTypedPropertyNullableIfCheckedRector::class);
};
