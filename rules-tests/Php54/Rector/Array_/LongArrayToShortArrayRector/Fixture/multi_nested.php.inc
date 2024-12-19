<?php

namespace Rector\Tests\Php54\Rector\Array_\LongArrayToShortArrayTest\Fixture;

final class MultiNested
{
    public function run()
    {
        return array(
            array(
                array(
                    'abc' => true,
                ),
            ),
        );
    }
}

?>
-----
<?php

namespace Rector\Tests\Php54\Rector\Array_\LongArrayToShortArrayTest\Fixture;

final class MultiNested
{
    public function run()
    {
        return [
            [
                [
                    'abc' => true,
                ],
            ],
        ];
    }
}

?>
