<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

final class BackslashWitNewlineWithoutAnotherTextWithOtherComment
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

final class BackslashWitNewlineWithoutAnotherTextWithOtherComment
{
    // some comment
    #[\Behat\Step\Then('then :value should \\')]
    public function someStep(): void
    {
    }
}

?>
