<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class CurlyInCondNoOpenBracketNext
{
    public function execute()
    {
         if ($a !== $b ||

            (function() {})()

            )

            return;

    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class CurlyInCondNoOpenBracketNext
{
    public function execute()
    {
         if ($a !== $b ||

            (function() {})()) {
             return;
         }

    }
}

?>
