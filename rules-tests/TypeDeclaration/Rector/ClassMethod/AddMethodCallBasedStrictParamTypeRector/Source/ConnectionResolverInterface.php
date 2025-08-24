<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Source;

interface ConnectionResolverInterface
{
    /**
     * @param  string|null  $name
     * @return ConnectionInterface
     */
    public function connection($name = null);
}
