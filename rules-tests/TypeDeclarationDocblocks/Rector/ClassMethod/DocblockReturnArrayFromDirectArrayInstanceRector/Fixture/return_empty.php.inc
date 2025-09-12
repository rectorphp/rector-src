<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

class ReturnEmpty
{
    public function run(): array
    {
        return [];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

class ReturnEmpty
{
    /**
     * @return array<int, mixed>
     */
    public function run(): array
    {
        return [];
    }
}

?>