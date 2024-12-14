<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

final class WithValueAsArgumentAndDescription
{
    /**
     * original comment
     *
     * @When(key="value") some description
     *
     * other comment
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
    /**
     * original comment
     *
     * other comment
     */
    #[\Behat\Step\When('(key="value")')] // some description
    public function someStep(): void
    {
    }
}

?>
