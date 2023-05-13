<?php

namespace Rector\Tests\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector\Fixture;

final class SkipDoubleMethodSameVariable
{
    public function run()
    {
        $method = 'you';

        return $method;
    }

    public function go()
    {
        $method = 'me';

        return $method;
    }
}

?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector\Fixture;

final class SkipDoubleMethodSameVariable
{
    /**
     * @var string
     */
    private const METHOD = 'you';
    public function run()
    {
        return self::METHOD;
    }

    public function go()
    {
        $method = 'me';

        return $method;
    }
}

?>
