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

    public function run2($value)
    {
        if ($value) {

        } elseif (rand(0, 1)) ?>

            <div>Hello</div>

        <?php
    }

    public function run3($value)
    {
        if ($value) {

        } else ?>

            <div>Hello</div>

        <?php
    }
}
