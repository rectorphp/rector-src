<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\ArrayMergeOfNonArraysToSimpleArrayRector\Fixture;

class NestedArrays
{
    public function go()
    {
        $values = array_merge(
            [
                ['foo' => 'bar'],
                ...$this->deconstructable(),
            ]
        );

        return $values;
    }

    private function deconstructable()
    {
        return ['rector'];
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\ArrayMergeOfNonArraysToSimpleArrayRector\Fixture;

class NestedArrays
{
    public function go()
    {
        $values = [['foo' => 'bar'], ...$this->deconstructable()];

        return $values;
    }

    private function deconstructable()
    {
        return ['rector'];
    }
}

?>
