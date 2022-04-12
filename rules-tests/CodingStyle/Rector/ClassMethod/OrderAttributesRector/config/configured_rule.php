<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassMethod\OrderAttributesRector;
use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderAttributesRector\Source\FirstAttribute;
use Rector\Tests\CodingStyle\Rector\ClassMethod\OrderAttributesRector\Source\SecondAttribute;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(OrderAttributesRector::class)
        ->configure([FirstAttribute::class, SecondAttribute::class]);
};
