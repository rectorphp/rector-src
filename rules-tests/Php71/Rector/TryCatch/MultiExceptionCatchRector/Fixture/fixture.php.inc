<?php

namespace Rector\Tests\Php71\Rector\TryCatch\MultiExceptionCatchRector\Fixture;

function multiExceptionCatch()
{
    try {
        // Some code...
    } catch (ExceptionType1 $e) {
        $e = 1;
    } catch (ExceptionType2 $e2) {
        $e = 1;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php71\Rector\TryCatch\MultiExceptionCatchRector\Fixture;

function multiExceptionCatch()
{
    try {
        // Some code...
    } catch (ExceptionType1|ExceptionType2 $e) {
        $e = 1;
    }
}

?>
