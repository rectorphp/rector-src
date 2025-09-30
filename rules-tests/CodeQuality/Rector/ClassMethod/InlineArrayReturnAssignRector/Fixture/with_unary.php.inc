<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class WithUnary
{
    public function run()
    {
        $data[0]['date'] = '2025-09-15';
        $data[0]['plus'] = +50000;
        $data[1]['minus'] = -67500;
        return $data;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class WithUnary
{
    public function run()
    {
        return [['date' => '2025-09-15', 'plus' => +50000], ['minus' => -67500]];
    }
}

?>
