<?php

declare(strict_types=1);

use PHPStan\Reflection\ClassReflection;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Config\RectorConfig;
use Rector\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector;

return RectorConfig::configure()
    ->withConfiguredRule(PrivatizeFinalClassMethodRector::class, [
        PrivatizeFinalClassMethodRector::SHOULD_SKIP_CALLBACK => static function (
            ClassMethod $classMethod,
            ClassReflection $classReflection,
        ): bool {
            if (! str_contains($classReflection->getName(), 'FinalClassWithProtectedMethods')) {
                return false;
            }


            return str_contains($classMethod->name->toString(), 'shouldBeProtected');
        },
    ]);
