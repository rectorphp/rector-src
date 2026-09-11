<?php

namespace Rector\Tests\Php84\Rector\Foreach_\ForeachToArrayAnyRector\Fixture;

class EarlyReturnSkipIfWithElseIfElse
{
    public function run($host, array $noProxyArray)
    {
        foreach ($noProxyArray as $area) {
            if ($area === '*') {
                return true;
            } elseif (empty($area)) {
                continue;
            } elseif ($area === $host) {
                return true;
            } else {
                $area = '.' . ltrim($area, '.');
                if (substr($host, -(strlen($area))) === $area) {
                    return true;
                }
            }
        }

        return false;
    }
}
