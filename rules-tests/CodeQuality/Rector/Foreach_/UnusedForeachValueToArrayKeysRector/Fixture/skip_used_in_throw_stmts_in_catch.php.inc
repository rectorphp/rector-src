<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\UnusedForeachValueToArrayKeysRector\Fixture;

/**
 * @see https://3v4l.org/DHPd4a
 */
final class SkipUsedInThrowStmtsInCatch
{
    public function run(array $data): void
    {
        try {
            foreach ($data as $key =>  $val) {
                throw new \Exception('test');
            }
        } catch (\Throwable) {
            echo 'Failed at value '.$val;
        }
	}
}
