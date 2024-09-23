<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\Fixture;

final class TranslationFunction
{
    public function run(string $pattern)
    {
        echo _("My translatable text with '{$pattern}'.");
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\Fixture;

final class TranslationFunction
{
    public function run(string $pattern)
    {
        echo _(sprintf("My translatable text with '%s'.", $pattern));
    }
}

?>

