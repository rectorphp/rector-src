<?php

namespace Rector\Tests\DowngradePhp74\Rector\ClassMethod\DowngradeContravariantArgumentTypeRector;

/**
 * @method InterfaceWithPhpdocMethod someMethod(string|null $param)
 */
interface InterfaceWithPhpdocMethod
{
}

class ContextImpl implements InterfaceWithPhpdocMethod
{
    public function someMethod(?string $param): self
    {
        return $this;
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp74\Rector\ClassMethod\DowngradeContravariantArgumentTypeRector;

/**
 * @method InterfaceWithPhpdocMethod someMethod(string|null $param)
 */
interface InterfaceWithPhpdocMethod
{
}

class ContextImpl implements InterfaceWithPhpdocMethod
{
    public function someMethod(?string $param): self
    {
        return $this;
    }
}

?>
