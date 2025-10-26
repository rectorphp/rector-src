<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source;

/**
 * @template T
 */
abstract class AbstractGenericFactory
{
    /**
     * @return T
     */
    abstract public function build(): object;
}