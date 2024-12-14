<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

use Behat\Step\Then;

final class MissedSlashes
{
    /**
     * @Then some message
     */
    public function theFuelOrderTargetFuelShouldHaveASource(string $source): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

use Behat\Step\Then;

final class MissedSlashes
{
    #[\Behat\Step\Then('some message')]
    public function theFuelOrderTargetFuelShouldHaveASource(string $source): void
    {
    }
}

?>
