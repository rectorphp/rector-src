<?php

namespace Rector\Tests\Issues\PrintMatchPhpstan\Fixture;

use ReflectionParameter;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\Config\Loader\FileLoader;

final class DefinitionFileLoader
{
    /**
     * @param ReflectionParameter[] $parameters
     */
    private function executeCallback(array $parameters, $configurator): void
    {
        $arguments = [];
        foreach ($parameters as $parameter) {
            $reflectionType = $parameter->getType();

            $arguments[] = match ($reflectionType->getName()) {
                DefinitionConfigurator::class => $configurator,
                FileLoader::class, self::class => $this,
            };
        }
    }
}
