<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\CompactToVariablesRector\Fixture;

final class UncompactNextUses
{
    public function run()
    {
        $one = 1000;
        $two = 'hey';

        $names = ['one', 'two'];

        $misc = compact($names);

        return compact($names) + $misc;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\CompactToVariablesRector\Fixture;

final class UncompactNextUses
{
    public function run()
    {
        $one = 1000;
        $two = 'hey';

        $names = ['one', 'two'];

        $misc = ['one' => $one, 'two' => $two];

        return ['one' => $one, 'two' => $two] + $misc;
    }
}

?>
