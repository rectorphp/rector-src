<?php

namespace Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Fixture;

use Symfony\Component\DependencyInjection\Container;

class SkipDotName
{
    public function run()
    {
        $someType = $this->getService('some.type');
    }

    /**
     * @template TService as object
     *
     * @param class-string<TService> $type
     * @return TService
     */
    public function getService(string $type): object
    {
        /** @var Container $container */
        $container = self::$container;

        return $container->get($type);
    }
}
