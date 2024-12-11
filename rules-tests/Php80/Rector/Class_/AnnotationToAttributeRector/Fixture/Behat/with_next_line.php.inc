<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

final class WithNextLine
{
    /**
     * @Then then :value should \
     * continue here
     */
    public function someStep(): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

final class WithNextLine
{
    #[\Behat\Step\Then('then :value should continue here')]
    public function someStep(): void
    {
    }
}

?>
