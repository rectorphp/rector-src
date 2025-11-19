<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

use Behat\Step\When;

final class WithMixedAttributeAndAnnotations
{
    /**
     * @When /^I have a step using annotations$/
     */
    public function hadAnnotations(): void
    {
    }

    #[When('/^I have a step using attributes$/')]
    public function hadAttributes(): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

use Behat\Step\When;

final class WithMixedAttributeAndAnnotations
{
    #[\Behat\Step\When('/^I have a step using annotations$/')]
    public function hadAnnotations(): void
    {
    }

    #[When('/^I have a step using attributes$/')]
    public function hadAttributes(): void
    {
    }
}

?>
