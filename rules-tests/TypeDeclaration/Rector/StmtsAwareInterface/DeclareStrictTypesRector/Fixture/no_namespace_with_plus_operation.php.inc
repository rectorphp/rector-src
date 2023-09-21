<?php

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector\Fixture;

final class NoNamespaceWithOperationPlusOperation
{
    private array $prop2;

    public function resolve2(array $second)
    {
        return $this->prop2 + $second;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector\Fixture;

final class NoNamespaceWithOperationPlusOperation
{
    private array $prop2;

    public function resolve2(array $second)
    {
        return $this->prop2 + $second;
    }
}
