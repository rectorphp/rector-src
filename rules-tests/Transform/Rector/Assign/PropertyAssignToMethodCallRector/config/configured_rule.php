<?php

declare(strict_types=1);

use Rector\Tests\Transform\Rector\Assign\PropertyAssignToMethodCallRector\Source\ChoiceControl;
use Rector\Transform\Rector\Assign\PropertyAssignToMethodCallRector;
use Rector\Transform\ValueObject\PropertyAssignToMethodCall;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(PropertyAssignToMethodCallRector::class)
        ->configure([
            new PropertyAssignToMethodCall(ChoiceControl::class, 'checkAllowedValues', 'checkDefaultValue'),
        ]);
};
