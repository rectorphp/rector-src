<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector\Fixture;

function skipSameBoolReturnFalse(): false
{
    return false;
}

function skipSameBoolReturn(): bool
{
    if (! skipSameBoolReturnFalse()) {
        return true;
    }

    return true;
}
