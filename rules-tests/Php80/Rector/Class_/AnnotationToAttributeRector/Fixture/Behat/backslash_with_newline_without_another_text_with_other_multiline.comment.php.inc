<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

final class BackslashWitNewlineWithoutAnotherTextWithOtherMultilineComment
{
    /**
     * some multiline
     *
     * comment
     */
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

final class BackslashWitNewlineWithoutAnotherTextWithOtherMultilineComment
{
    /**
     * some multiline
     *
     * comment
     */
    #[\Behat\Step\Then('then :value should \\')]
    public function someStep(): void
    {
    }
}

?>
