<?php

declare(strict_types=1);

namespace Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Source;

class XmlResource
{
    public $resource;

    public function __construct()
    {
        $this->resource = new \SimpleXMLElement('<root/>');
    }
}
