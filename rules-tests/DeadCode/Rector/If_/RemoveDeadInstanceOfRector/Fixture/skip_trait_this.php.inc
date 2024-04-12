<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveDeadInstanceOfRector\Fixture;

use DateTimeInterface;

trait SkipTraitThis
{
    public function getIssue(): void
    {
        foreach (['value_1', 'value_2'] as $value) {
            if ($this instanceof DateTimeInterface && $this->getExample() === false) {
                $this->setExample('demo');
            }
        }
    }
}
