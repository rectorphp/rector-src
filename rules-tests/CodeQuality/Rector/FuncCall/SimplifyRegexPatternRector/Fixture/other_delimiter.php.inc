<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SimplifyRegexPatternRector\Fixture;

use Nette\Utils\Strings;

class OtherDelimiter
{
    public function run($value)
    {
        preg_match('([0-9])', $value);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SimplifyRegexPatternRector\Fixture;

use Nette\Utils\Strings;

class OtherDelimiter
{
    public function run($value)
    {
        preg_match('(\d)', $value);
    }
}

?>
