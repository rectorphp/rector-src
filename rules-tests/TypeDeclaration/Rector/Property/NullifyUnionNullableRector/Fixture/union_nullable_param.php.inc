<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\VarAnnotationIncorrectNullableRector\Fixture;

final class UnionNullableParam
{
    public function run(null|array $param) {}
    public function run2(null|array $param) {}
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\VarAnnotationIncorrectNullableRector\Fixture;

final class UnionNullableParam
{
    public function run(?array $param) {}
    public function run2(?array $param) {}
}

?>
