<?php

namespace Rector\Core\Tests\Issues\PlainValueParser\Source;

class CustomAnnotation
{
    public function __construct(public string $description)
    {
    }
}
