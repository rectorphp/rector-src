<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

final class DisjointNewlineMulti
{
    /**
     * @Then then :value should
     *
     * Unrelated comment
     * with multi line
     */
    public function someStep(): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

final class DisjointNewlineMulti
{
    /**
     * Unrelated comment
     * with multi line
     */
    #[\Behat\Step\Then('then :value should')]
    public function someStep(): void
    {
    }
}

?>
