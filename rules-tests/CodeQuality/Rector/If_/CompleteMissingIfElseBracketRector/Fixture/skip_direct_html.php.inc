<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class SkipDirectHTML
{
    public function run($value)
    {
        if ($value) ?>
            <div>Hello</div>
        <?php
    }
}
