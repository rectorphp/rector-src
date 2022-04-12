<?php

declare(strict_types=1);

use Rector\Tests\Transform\Rector\StaticCall\StaticCallToNewRector\Source\SomeJsonResponse;
use Rector\Transform\Rector\StaticCall\StaticCallToNewRector;
use Rector\Transform\ValueObject\StaticCallToNew;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(StaticCallToNewRector::class)
        ->configure([new StaticCallToNew(SomeJsonResponse::class, 'create')]);
};
