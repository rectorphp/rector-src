<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

final class BackslashWitNewlineWithoutAnotherTextWithOtherComment2
{
    // some comment
    /**
     * @Then then :value should \
     *
     *
     *
     */
    public function someStep(): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

final class BackslashWitNewlineWithoutAnotherTextWithOtherComment2
{
    // some comment
    #[\Behat\Step\Then('then :value should \\')]
    public function someStep(): void
    {
    }
}

?>
