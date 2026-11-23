<?php

namespace Rector\Tests\Php70\Rector\FuncCall\RandomFunctionRector\Fixture;

final class ConstantTypeBounds
{
    private const MAX = 10;

    public function run()
    {
        $min = 5;
        rand($min, self::MAX);

        $high = 20;
        rand($high, self::MAX);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\FuncCall\RandomFunctionRector\Fixture;

final class ConstantTypeBounds
{
    private const MAX = 10;

    public function run()
    {
        $min = 5;
        random_int($min, self::MAX);

        $high = 20;
        random_int(self::MAX, $high);
    }
}

?>
