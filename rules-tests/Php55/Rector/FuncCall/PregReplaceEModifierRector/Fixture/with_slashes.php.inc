<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

final class WithSlashes
{
    public function run($input): void
    {
        $result = preg_replace(
            "/([\300-\310])/e",
            "ord('\\1')",
            $input
        );
    }
}

?>
-----
<?php

namespace Rector\Tests\Php55\Rector\FuncCall\PregReplaceEModifierRector\Fixture;

final class WithSlashes
{
    public function run($input): void
    {
        $result = preg_replace_callback(
            "/([\xc0-\xc8])/",
            function ($matches) {
                return ord($matches[1]);
            },
            $input
        );
    }
}

?>
