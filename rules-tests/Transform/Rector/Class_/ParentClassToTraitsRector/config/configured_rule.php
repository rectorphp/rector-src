<?php

declare(strict_types=1);

use Rector\Tests\Transform\Rector\Class_\ParentClassToTraitsRector\Source\AnotherParentObject;
use Rector\Tests\Transform\Rector\Class_\ParentClassToTraitsRector\Source\ParentObject;
use Rector\Tests\Transform\Rector\Class_\ParentClassToTraitsRector\Source\SecondTrait;
use Rector\Tests\Transform\Rector\Class_\ParentClassToTraitsRector\Source\SomeTrait;
use Rector\Transform\Rector\Class_\ParentClassToTraitsRector;
use Rector\Transform\ValueObject\ParentClassToTraits;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ParentClassToTraitsRector::class)
        ->configure([
            new ParentClassToTraits(ParentObject::class, [SomeTrait::class]),
            new ParentClassToTraits(AnotherParentObject::class, [SomeTrait::class, SecondTrait::class]),
        ]);
};
