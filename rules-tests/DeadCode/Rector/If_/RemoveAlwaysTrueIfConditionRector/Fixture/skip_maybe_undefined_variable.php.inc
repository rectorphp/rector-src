<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

class SkipMaybeUndefinedVariable
{
    public function handle(string $username, string $password, object $response): void
    {
        if ($username !== 'admin') {
            $error = true;
        }

        if ($password !== 'secret') {
            $error = true;
        }

        if ($error) {
            $response->setAttribute('status', 'ERROR');
        }
    }
}
