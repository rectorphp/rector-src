<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\SimplifyTautologyTernaryRector\Fixture;

final class IdenticalCompareLeft
{
    public function run(string $param)
    {
        $foo = $this->bar($param) === '' ? '' : $this->bar($param);
		    echo 'asd' . $foo . 'def';
    }

    private function bar(string $value)
    {
        return trim($value);
    }
}
?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\SimplifyTautologyTernaryRector\Fixture;

final class IdenticalCompareLeft
{
    public function run(string $param)
    {
        $foo = $this->bar($param);
		    echo 'asd' . $foo . 'def';
    }

    private function bar(string $value)
    {
        return trim($value);
    }
}

?>
