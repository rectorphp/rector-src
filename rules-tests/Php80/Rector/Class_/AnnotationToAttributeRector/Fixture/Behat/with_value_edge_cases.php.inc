<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

final class WithValueEdgeCases
{
    /**
     * @Then then :value should be have been finished
     */
    public function someStep(): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

final class WithValueEdgeCases
{
    #[\Behat\Step\Then('then :value should be have been finished')]
    public function someStep(): void
    {
    }
}

?>
