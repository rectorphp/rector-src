<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

abstract class ChangeAbstractMethod
{
    public function run()
    {
        return new static();
    }
}

class ChangeAbstractMethodChild extends ChangeAbstractMethod
{
    public function run(): static
    {
        return new static();
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Fixture;

abstract class ChangeAbstractMethod
{
    public function run(): static
    {
        return new static();
    }
}

class ChangeAbstractMethodChild extends ChangeAbstractMethod
{
    public function run(): static
    {
        return new static();
    }
}

?>
