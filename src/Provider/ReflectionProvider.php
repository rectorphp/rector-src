<?php

declare(strict_types=1);

namespace Rector\Core\Provider;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider as PHPStanReflectionProvider;

final class ReflectionProvider
{
    public function __construct(
        private readonly PHPStanReflectionProvider $reflectionProvider,
        private readonly CurrentFileProvider $currentFileProvider
    ) {
    }

    public function __call($name, $params)
    {
        return $this->reflectionProvider->$name(...$params);
    }

    public function getClass(string $className): ClassReflection
    {
        if (! $this->reflectionProvider->hasClass($className)) {
            dump('here');
        }

        return $this->reflectionProvider->getClass($className);
    }
}
