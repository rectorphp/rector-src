<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class WithDuplicatedNestedArray
{
    public static function run(): iterable
    {
        $testingUrls = [
            'test',
            'test?important_parameter=1',
        ];

        return [
            '$cacheQueryString=false' => [false, 1, $testingUrls],
            '$cacheQueryString=true' => [true, 5, $testingUrls],
            '$cacheQueryString=array' => [['important_parameter'], 3, $testingUrls],
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

final class WithDuplicatedNestedArray
{
    /**
     * @return array<string, string[][]|bool[]|int[]>
     */
    public static function run(): iterable
    {
        $testingUrls = [
            'test',
            'test?important_parameter=1',
        ];

        return [
            '$cacheQueryString=false' => [false, 1, $testingUrls],
            '$cacheQueryString=true' => [true, 5, $testingUrls],
            '$cacheQueryString=array' => [['important_parameter'], 3, $testingUrls],
        ];
    }
}

?>
