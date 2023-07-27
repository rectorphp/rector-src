<?php

declare(strict_types=1);

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

use Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Source\WithByRefMethod;

final class SkipCallByRefMethodScope
{
    public function __construct(
        private object $output
    ) {
    }

    public function prepare(): void
    {
        $obj = new WithByRefMethod();
        $obj->install($this->output);
    }
}
