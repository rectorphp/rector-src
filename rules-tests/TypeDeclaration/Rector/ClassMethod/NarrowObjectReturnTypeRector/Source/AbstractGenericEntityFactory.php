<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source;

/**
 * @template TEntity of object
 */
abstract class AbstractGenericEntityFactory
{
    /**
     * @return TEntity
     */
    abstract protected function build(): object;

    /**
     * @return TEntity
     */
    public function create(): object
    {
        return $this->build();
    }
}