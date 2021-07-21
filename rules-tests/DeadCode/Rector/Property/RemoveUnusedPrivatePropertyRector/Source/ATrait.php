<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Source;

trait ATrait
{
    public function run()
    {
        if ($this->aProperty) {
            $this->aProperty->execute();
        }
    }
}
