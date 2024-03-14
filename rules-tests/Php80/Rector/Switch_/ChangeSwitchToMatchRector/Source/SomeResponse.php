<?php

declare(strict_types=1);

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Source;

final class SomeResponse
{
    public function __construct($content, $statusCode)
    {
    }

    public static function build($a, $b): self
    {
        return new self($a, $b);
    }
}
