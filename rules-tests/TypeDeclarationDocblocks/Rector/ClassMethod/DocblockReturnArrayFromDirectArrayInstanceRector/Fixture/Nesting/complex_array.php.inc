<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture\Nesting;

class ComplexArray
{
   public function run(): array
    {
        return [
            'other' => false,
            'context' => [
                'id' => 1,
                'data' => [
                    'foo' => 'bar',
                    'baz' => 'qux',
                ],
            ],
            'values' => [
                'name' => '111',
                'age' => 1,
            ],
            'values_other' => [
                'name' => '111',
                'age' => 1,
            ],
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture\Nesting;

class ComplexArray
{
   /**
    * @return array<string, array<string, array<string, string>|int>|array<string, int|string>|bool>
    */
   public function run(): array
    {
        return [
            'other' => false,
            'context' => [
                'id' => 1,
                'data' => [
                    'foo' => 'bar',
                    'baz' => 'qux',
                ],
            ],
            'values' => [
                'name' => '111',
                'age' => 1,
            ],
            'values_other' => [
                'name' => '111',
                'age' => 1,
            ],
        ];
    }
}

?>
