<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

class StaticArray
{
    public function run()
    {
        $value   = 'foo';
        $content = [
            1     => $value,
            'bar',
            'baz' => [
                'qux',
            ],
        ];

        if (\rand(0, 1)) {
            echo '';
        }

        return $content;
    }
}

?>