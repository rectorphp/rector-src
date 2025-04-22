<?php

declare(strict_types=1);

namespace Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Source;

// for testing under php 8.0 to avoid flip flop ClassReflection cache
class XmlResource2
{
    public $resource;

    public function __construct()
    {
        $this->resource = new \SimpleXMLElement('<root/>');
    }
}
