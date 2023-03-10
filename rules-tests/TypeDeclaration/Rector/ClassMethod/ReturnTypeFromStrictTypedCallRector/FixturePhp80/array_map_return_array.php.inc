<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\FixturePhp80;

final class ArrayMapReturnArray
{
    public function run()
    {
        $range = static function ($start, $end) {
            return array_map(static function ($number) {
                return sprintf('%02d', $number);
            }, range($start, $end));
        };
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\FixturePhp80;

final class ArrayMapReturnArray
{
    public function run()
    {
        $range = static function ($start, $end): array {
            return array_map(static function ($number): string {
                return sprintf('%02d', $number);
            }, range($start, $end));
        };
    }
}

?>
