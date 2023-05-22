<?php

namespace Rector\Core\Tests\Issues\IndentationCrash\Fixture;

final class CrashIndentationInFor
{
    public function search($rates, $type, $cpt)
    {
        for ($i = 0; $i < sizeof($rates); $i++) {
            // one two three
        }

        return 0.0;
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\IndentationCrash\Fixture;

final class CrashIndentationInFor
{
    public function search($rates, $type, $cpt)
    {
        for ($i = 0; $i < (is_countable($rates) ? count($rates) : 0); $i++) {
            // one two three
        }

        return 0.0;
    }
}

?>
