<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

final class MagicInvoke
{
    public function __invoke()
    {
        echo 'test';
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

final class MagicInvoke
{
    public function __invoke(): void
    {
        echo 'test';
    }
}

?>
