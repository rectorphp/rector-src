<?php

namespace Rector\Tests\Issues\PlainValueParser\Source;

class CustomAnnotation
{
    public function __construct(public string $description)
    {
    }
}
