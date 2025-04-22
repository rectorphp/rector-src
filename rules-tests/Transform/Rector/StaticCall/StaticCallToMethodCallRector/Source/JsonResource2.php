<?php

declare(strict_types=1);

namespace Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Source;

// for testing under php 7.4 to avoid flip flop ClassReflection cache
class JsonResource2
{
    public $resource;

    public function __construct($resource)
    {
        $this->resource = $resource;
    }
}
