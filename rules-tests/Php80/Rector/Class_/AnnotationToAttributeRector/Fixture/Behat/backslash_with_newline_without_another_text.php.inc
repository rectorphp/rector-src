<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

final class BackslashWitNewlineWithoutAnotherText
{
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

final class BackslashWitNewlineWithoutAnotherText
{
    #[\Behat\Step\Then('then :value should \\')]
    public function someStep(): void
    {
    }
}

?>
