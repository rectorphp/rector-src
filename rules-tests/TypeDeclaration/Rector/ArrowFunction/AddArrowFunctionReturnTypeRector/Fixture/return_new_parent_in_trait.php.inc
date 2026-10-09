<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

trait ReturnNewParentInTrait
{
    public function __construct(...$args)
    {
        self::createLazyProxy(static fn () => new parent(...$args), $this);
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

trait ReturnNewParentInTrait
{
    public function __construct(...$args)
    {
        self::createLazyProxy(static fn (): parent => new parent(...$args), $this);
    }
}

?>
