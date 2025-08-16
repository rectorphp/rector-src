<?php

declare(strict_types=1);

use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ClassReflection;
use Rector\Config\RectorConfig;
use Rector\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector;

return RectorConfig::configure()
    ->withConfiguredRule(PrivatizeFinalClassPropertyRector::class, [
        PrivatizeFinalClassPropertyRector::SHOULD_SKIP_CALLBACK => static function (
            Property|string $property,
            ClassReflection $classReflection,
        ): bool {
            if (! str_contains($classReflection->getName(), 'FinalClassWithProtectedProperties')) {
                return false;
            }

            $name = $property instanceof Property
                ? (string) $property->props[0]->name
                : $property;

            return str_contains($name, 'shouldBeProtected');
        },
    ]);
