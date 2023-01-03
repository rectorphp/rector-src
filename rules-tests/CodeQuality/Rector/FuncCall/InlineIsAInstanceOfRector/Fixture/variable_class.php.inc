<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\InlineIsAInstanceOfRector\Fixture;

class VariableClass
{
    /**
     * @param class-string<\PHPStan\Type\Type> $type
     */
    public function run(object $object, string $type)
    {
        return is_a($object, $type);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\InlineIsAInstanceOfRector\Fixture;

class VariableClass
{
    /**
     * @param class-string<\PHPStan\Type\Type> $type
     */
    public function run(object $object, string $type)
    {
        return $object instanceof $type;
    }
}

?>
