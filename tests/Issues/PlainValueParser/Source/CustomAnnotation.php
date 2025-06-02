<?php

namespace Rector\Tests\Issues\PlainValueParser\Source;

#[\Attribute]
final class CustomAnnotation
{
    public function __construct(public string $description)
    {
    }
}
