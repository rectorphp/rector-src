<?php

namespace Rector\Tests\Issues\PlainValueParser\Source;

final class CustomAnnotation
{
    public function __construct(public string $description)
    {
    }
}
