<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\CompactToVariablesRector\Fixture;

final class CompactWithExtract
{
    public function run()
    {
        $values = ['result'];

        $result = 1000;

        return compact($values);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\CompactToVariablesRector\Fixture;

final class CompactWithExtract
{
    public function run()
    {
        $values = ['result'];

        $result = 1000;

        return ['result' => $result];
    }
}

?>
