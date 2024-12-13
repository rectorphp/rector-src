<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

final class DisjointNewline
{
    /**
     * @Then then :value should
     *
     * Unrelated comment
     */
    public function someStep(): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

final class DisjointNewline
{
    /**
     * Unrelated comment
     */
    #[\Behat\Step\Then('then :value should')]
    public function someStep(): void
    {
    }
}

?>
