<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture\Nesting;

final class AvoidExtremeNesting
{
   public function run(): array
    {
        return [
            'key1' => 123,
            'key2' => '456.7',
            'key3' => 89.0,
            'key4' => -42,
            'key5' => '12%',
            'key6' => -9999,
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
    * @return array<string, int|string|float>
    */
   public function run(): array
    {
        return [
            'key1' => 123,
            'key2' => '456.7',
            'key3' => 89.0,
            'key4' => -42,
            'key5' => '12%',
            'key6' => -9999,
        ];
    }
}

?>
