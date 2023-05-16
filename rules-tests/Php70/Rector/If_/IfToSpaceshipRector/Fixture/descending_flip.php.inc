<?php

namespace Rector\Tests\Php70\Rector\If_\IfToSpaceshipRector\Fixture;

final class DescendingFlip
{
    public function run(array $languages)
    {
        usort($languages, function ($firstLanguage, $secondLanguage) {
            if ($firstLanguage[0] === $secondLanguage[0]) {
                return 0;
            } else {
                return ($secondLanguage[0] < $firstLanguage[0]) ? -1 : 1;
            }
        });
    }
}

?>

-----
<?php

namespace Rector\Tests\Php70\Rector\If_\IfToSpaceshipRector\Fixture;

final class DescendingFlip
{
    public function run(array $languages)
    {
        usort($languages, function ($firstLanguage, $secondLanguage) {
            return $secondLanguage[0] <=> $firstLanguage[0];
        });
    }
}

?>

