<?php

namespace Rector\Tests\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector\Fixture;

use Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\VariableTypeResolver\Source\AnotherType;

final class EmptyOnExactType
{
    public function run()
    {
        $exactType = $this->getExactType();
        if (empty($exactType)) {
            return;
        }
    }

    private function getExactType(): ?AnotherType
    {
        if (mt_rand(0, 1)) {
            return new AnotherType();
        }

        return null;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector\Fixture;

use Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\VariableTypeResolver\Source\AnotherType;

final class EmptyOnExactType
{
    public function run()
    {
        $exactType = $this->getExactType();
        if (!$exactType instanceof \Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\VariableTypeResolver\Source\AnotherType) {
            return;
        }
    }

    private function getExactType(): ?AnotherType
    {
        if (mt_rand(0, 1)) {
            return new AnotherType();
        }

        return null;
    }
}

?>
