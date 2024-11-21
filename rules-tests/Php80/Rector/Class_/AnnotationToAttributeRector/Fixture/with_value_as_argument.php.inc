<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

final class WithValueAsArgument
{
    /**
     * @When this is a simple annotation with a value
     * @When
     * @When "this value is within quotes"
     * @When this value has a ' character
     * @When(key="value") this annotation has parameters so won't use this option
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
    #[\Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\Behat\When('this is a simple annotation with a value')]
    #[\Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\Behat\When]
    #[\Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\Behat\When('"this value is within quotes"')]
    #[\Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\Behat\When('this value has a \' character')]
    #[\Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\Behat\When(key: 'value')] // this annotation has parameters so won't use this option
    public function someStep(): void
    {
    }
}

?>
