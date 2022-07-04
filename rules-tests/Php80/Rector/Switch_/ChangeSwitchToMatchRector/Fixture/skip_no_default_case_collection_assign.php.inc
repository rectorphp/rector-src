<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

final class SkipNoDefaultCaseCollectionAssign
{
    function foo(string $var): void {
        switch ($var) {
            case 'hey':
                $var = 'oh';
                break;
            case 'foo':
            case 'bar':
                $var = "baz";
                break;
        }

        dump($var);
    }
}