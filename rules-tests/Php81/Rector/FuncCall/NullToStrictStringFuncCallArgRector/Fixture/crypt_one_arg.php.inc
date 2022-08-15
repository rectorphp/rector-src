<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

/**
 * Crypt requires 2 args since php 5.6, when it only fill 1st arg, it will only change 1st arg
 *
 * Note: Filling 2nd arg will require different Rule to match the salt algorithm.
 */
final class CryptOneArg
{
    public function run($subject)
    {
        crypt($subject);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

/**
 * Crypt requires 2 args since php 5.6, when it only fill 1st arg, it will only change 1st arg
 *
 * Note: Filling 2nd arg will require different Rule to match the salt algorithm.
 */
final class CryptOneArg
{
    public function run($subject)
    {
        crypt((string) $subject);
    }
}

?>
