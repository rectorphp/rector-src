<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SimplifyRegexPatternRector\Fixture;

class SlashDelimiter
{
    public function run(string $param)
    {
        return preg_match('/^[0-9]+$/', $param);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SimplifyRegexPatternRector\Fixture;

class SlashDelimiter
{
    public function run(string $param)
    {
        return preg_match('/^\d+$/', $param);
    }
}

?>
