<?php

namespace Rector\Tests\CodingStyle\Rector\Property\InlineSimplePropertyAnnotationRector\Fixture\DefaultConfig;

final class SkipUnconfiguredAnnotation
{
    /**
     * @unconfiguredAnnotation \DateTime[]|null A datetime!
     */
    private ?array $dateTimes;
}
?>
