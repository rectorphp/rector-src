<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

use JMS\Serializer\Annotation as JMS;

final class SkipJmsType
{
    #[JMS\Type('string')]
    private string $value;

    public function getValue(): string
    {
        return $this->value;
    }
}
