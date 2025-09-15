<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

class ComplexArray
{
   public function run(): array
    {
        return [
            'id' => new \stdClass(),
            'context' => [
                'id' => 1,
                'data' => [
                    'foo' => 'bar',
                    'baz' => 'qux',
                ],
            ],
            'values' => [
                'name' => '111',
            ],
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

class ComplexArray
{
   /**
    * @return array<string, \stdClass|array<string, int|string|array<string, string>>>
    */
   public function run(): array
    {
        return [
            'id' => new \stdClass(),
            'context' => [
                'id' => 1,
                'data' => [
                    'foo' => 'bar',
                    'baz' => 'qux',
                ],
            ],
            'values' => [
                'name' => '111',
            ],
        ];
    }
}

?>
