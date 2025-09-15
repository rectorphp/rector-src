<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture\Nesting;

final class TwoNestedLevels
{
   public function run(): array
    {
        return [
            'key' => [
                'id' => 1,
                'name' => '111',
            ],
            'another_key' => [
                'id' => 1,
                'name' => '111',
            ],
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture\Nesting;

final class TwoNestedLevels
{
   /**
    * @return array<string, array<string, int|string>>
    */
   public function run(): array
    {
        return [
            'key' => [
                'id' => 1,
                'name' => '111',
            ],
            'another_key' => [
                'id' => 1,
                'name' => '111',
            ],
        ];
    }
}

?>
