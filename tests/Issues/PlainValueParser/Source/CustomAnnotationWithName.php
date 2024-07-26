<?php


namespace Rector\Tests\Issues\PlainValueParser\Source;

class CustomAnnotationWithName
{
    public function __construct(public string $description, public string $name)
    {
    }
}
