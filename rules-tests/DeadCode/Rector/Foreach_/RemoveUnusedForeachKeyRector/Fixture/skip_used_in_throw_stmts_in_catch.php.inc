<?php

namespace Rector\Tests\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector\Fixture;

/**
 * @see https://3v4l.org/i9j78
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
            echo 'Failed at index '.$key;
        }
	}
}