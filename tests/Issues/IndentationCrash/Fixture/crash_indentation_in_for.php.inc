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
        $ratesCount = count($rates);
        for ($i = 0; $i < $ratesCount; $i++) {
            // one two three
        }

        return 0.0;
    }
}

?>
