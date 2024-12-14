<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

final class WithValueAsArgument
{
    /**
     * @When this is a simple annotation with a value
     * @When
     * @When "this value is within quotes"
     * @When this value has a ' character
     * @When(key="value")
     */
    public function someStep(): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

final class WithValueAsArgument
{
    #[\Behat\Step\When('this is a simple annotation with a value')]
    #[\Behat\Step\When]
    #[\Behat\Step\When('"this value is within quotes"')]
    #[\Behat\Step\When('this value has a \' character')]
    #[\Behat\Step\When('(key="value")')]
    public function someStep(): void
    {
    }
}

?>
