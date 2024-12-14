<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

final class WithValueAsArgumentAndDescription
{
    /**
     * @When(key="value") some description
     */
    public function someStep(): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

final class WithValueAsArgumentAndDescription
{
    #[\Behat\Step\When('(key="value")')] // some description
    public function someStep(): void
    {
    }
}

?>
