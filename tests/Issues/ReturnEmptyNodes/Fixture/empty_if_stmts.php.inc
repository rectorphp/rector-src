<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\ReturnEmptyNodes\Fixture;

final class EmptyIfStmts
{
    public function run()
    {
        if (rand(0,1)) {
        }
    }
}
