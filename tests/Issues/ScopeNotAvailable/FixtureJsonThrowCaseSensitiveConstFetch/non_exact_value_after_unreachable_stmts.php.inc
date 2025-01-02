<?php

namespace Rector\Tests\Issues\ScopeNotAvailable\FixtureJsonThrowCaseSensitiveConstFetch;

class NonExactValueAfterUnreachableStmts
{
    /**
     * @param 'foo' $foo
     */
    public static function run($foo)
    {
        if ($foo === 'foo') {
            return;
        }

        echo 'some statement 1';
        echo 'some statement 2';
        echo(json_encode($foo));
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\ScopeNotAvailable\FixtureJsonThrowCaseSensitiveConstFetch;

class NonExactValueAfterUnreachableStmts
{
    /**
     * @param 'foo' $foo
     */
    public static function run($foo)
    {
        if ($foo === 'foo') {
            return;
        }

        echo 'some statement 1';
        echo 'some statement 2';
        echo(json_encode($foo, JSON_THROW_ON_ERROR));
    }
}

?>
