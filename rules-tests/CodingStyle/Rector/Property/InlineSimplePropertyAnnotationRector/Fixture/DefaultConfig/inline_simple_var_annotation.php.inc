<?php

namespace Rector\Tests\CodingStyle\Rector\Property\InlineSimplePropertyAnnotationRector\Fixture\DefaultConfig;

final class InlineSimpleVarAnnotation
{
    /**
     * @var \DateTime[]|null
     */
    private ?array $dateTimes;
}
?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Property\InlineSimplePropertyAnnotationRector\Fixture\DefaultConfig;

final class InlineSimpleVarAnnotation
{
    /** @var \DateTime[]|null */
    private ?array $dateTimes;
}
?>
