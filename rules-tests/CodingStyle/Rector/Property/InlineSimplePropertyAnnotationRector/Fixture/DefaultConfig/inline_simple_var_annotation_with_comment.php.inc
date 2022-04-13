<?php

namespace Rector\Tests\CodingStyle\Rector\Property\InlineSimplePropertyAnnotationRector\Fixture\DefaultConfig;

final class InlineSimpleVarAnnotationWithComment
{
    /**
     * @var \DateTime[]|null A datetime!
     */
    private ?array $dateTimes;
}
?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Property\InlineSimplePropertyAnnotationRector\Fixture\DefaultConfig;

final class InlineSimpleVarAnnotationWithComment
{
    /** @var \DateTime[]|null A datetime! */
    private ?array $dateTimes;
}
?>
