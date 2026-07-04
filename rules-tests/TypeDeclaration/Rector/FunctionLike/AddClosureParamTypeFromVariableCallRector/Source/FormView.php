<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromVariableCallRector\Source;

/**
 * @implements \ArrayAccess<string, self>
 */
final class FormView implements \ArrayAccess
{
    public function offsetExists($offset): bool
    {
        return true;
    }

    public function offsetGet($offset): self
    {
        return $this;
    }

    public function offsetSet($offset, $value): void
    {
    }

    public function offsetUnset($offset): void
    {
    }
}
