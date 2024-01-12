<?php

namespace Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Source\SortingClass;

final class ExternalClass
{
    /**
     * @var SortingClass
     */
    private $sortingClass;

    public function __construct(SortingClass $sortingClass)
    {
        $this->sortingClass = $sortingClass;
    }

    public function noGo($values)
    {
        $sortingClass = new SortingClass();

        usort($values, [$this->sortingClass, 'protectedSort']);
        usort($values, [$sortingClass, 'privateSort']);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector\Source\SortingClass;

final class ExternalClass
{
    /**
     * @var SortingClass
     */
    private $sortingClass;

    public function __construct(SortingClass $sortingClass)
    {
        $this->sortingClass = $sortingClass;
    }

    public function noGo($values)
    {
        $sortingClass = new SortingClass();

        usort($values, fn($a, $b) => $this->sortingClass->protectedSort($a, $b));
        usort($values, fn($a, $b) => $sortingClass->privateSort($a, $b));
    }
}

?>
