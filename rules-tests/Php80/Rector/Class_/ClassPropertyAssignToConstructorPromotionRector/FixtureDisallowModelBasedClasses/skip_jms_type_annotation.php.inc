<?php

namespace Rector\Tests\Php80\Rector\Class_\FixtureDisallowModelBasedClasses\Fixture;

use JMS\Serializer\Annotation as JMS;

final class SkipJmsTypeAnnotation
{
    /**
     * @JMS\Type('string)
     */
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
