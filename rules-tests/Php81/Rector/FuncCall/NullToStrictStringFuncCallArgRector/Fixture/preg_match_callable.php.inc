<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class PregMatchCallable
{
    public function run($regex)
    {
        preg_replace_callback($regex, 'value', function (array $match) use (
            &$string
        ): string {
            $innerPattern = $match['content'];
            $positionDelimiter = strpos($innerPattern, '');
        });
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class PregMatchCallable
{
    public function run($regex)
    {
        preg_replace_callback($regex, 'value', function (array $match) use (
            &$string
        ): string {
            $innerPattern = $match['content'];
            $positionDelimiter = strpos((string) $innerPattern, '');
        });
    }
}

?>
