<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

final class MissedSlashes
{
    /**
     * @Then some message
     */
    public function expect(string $source): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Behat;

final class MissedSlashes
{
    #[\Behat\Step\Then('some message')]
    public function expect(string $source): void
    {
    }
}

?>
