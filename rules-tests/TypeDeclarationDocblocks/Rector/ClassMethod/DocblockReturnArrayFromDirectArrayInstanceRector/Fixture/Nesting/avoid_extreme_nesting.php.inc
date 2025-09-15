<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture\Nesting;

final class AvoidExtremeNesting
{
   public function run(): array
    {
        return [
            'data' => [
                'data' => [
                    'data' => [
                        'data' => [
                            'data' => [
                                'name' => 'John',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture\Nesting;

final class AvoidExtremeNesting
{
   /**
    * @return array<string, array<string, array<string, array<string, mixed>>>>
    */
   public function run(): array
    {
        return [
            'data' => [
                'data' => [
                    'data' => [
                        'data' => [
                            'data' => [
                                'name' => 'John',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}

?>
