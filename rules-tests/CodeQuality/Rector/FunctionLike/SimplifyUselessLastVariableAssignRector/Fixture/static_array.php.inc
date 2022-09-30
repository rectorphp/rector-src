<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

class StaticArray
{
    public function run()
    {
        $content = [
            1     => 'foo',
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
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector\Fixture;

class StaticArray
{
    public function run()
    {
        if (\rand(0, 1)) {
            echo '';
        }

        return [
            1     => 'foo',
            'bar',
            'baz' => [
                'qux',
            ],
        ];
    }
}

?>
