<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class SkipNestedDimFetch
{
    public static function getData(): array
    {
        $data = [];

        $data['info'] = [
            'one' => 123,
        ];

        $data['info']['nested'] = 123;

        return $data;
    }
}

?>
