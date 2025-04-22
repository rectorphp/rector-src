<?php

declare(strict_types=1);

namespace Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Source;

class JsonResource
{
    public $resource;

    public function __construct($resource)
    {
        $this->resource = $resource;
    }
}
