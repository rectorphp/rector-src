<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

class SkipUsedInDynamicVariable
{
    public function run($length)
    {
        $cons = ['b', 'c'];
        $cons_cant_start = ['ck', 'cm'];
        $vows = ['a', 'e', ];
        $current = (random_int(0, 1) === 0 ? 'cons' : 'vows');
        $word = '';

        while (strlen($word) < $length) {
            if (strlen($word) === 2) {
                $cons = array_merge($cons, $cons_cant_start);
            }
            $rnd = ${$current}[random_int(0, count(${$current}) - 1)];
            if (strlen($word . $rnd) <= $length) {
                $word .= $rnd;
                $current = ($current === 'cons' ? 'vows' : 'cons');
            }
        }

        return $word;
    }
}
