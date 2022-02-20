<?php

declare(strict_types=1);

namespace Rector\Core\Configuration\Reflection;

use Symplify\PackageBuilder\Reflection\PrivatesAccessor;

final class ArgumentAndParameterFactory
{
    private PrivatesAccessor $privatesAccessor;

    public function __construct()
    {
        $this->privatesAccessor = new PrivatesAccessor();
    }

    /**
     * @param class-string $className
     *
     * @param array<string, mixed> $arguments
     * @param array<string, mixed> $properties
     */
    public function create(string $className, array $arguments, array $properties): object
    {
        $object = new $className(...$arguments);

        foreach ($properties as $name => $value) {
            $this->privatesAccessor->setPrivateProperty($object, $name, $value);
        }

        return $object;
    }
}
