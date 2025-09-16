<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture\Nesting;

class ComplexArray
{
   public function run(): array
    {
        return [
            'id' => new \stdClass(),
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
    * @return array<string, mixed>
    */
   public function run(): array
    {
        return [
            'id' => new \stdClass(),
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
