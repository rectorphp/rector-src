<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

final class WithSmallLetter
{
    /**
     * @then then :value should
     */
    public function someStep(): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

final class WithSmallLetter
{
    #[\Behat\Step\Then('then :value should')]
    public function someStep(): void
    {
    }
}

?>
