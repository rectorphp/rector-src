<?php

namespace Rector\Tests\Php71\Rector\TryCatch\MultiExceptionCatchRector\Fixture;

final class MultipleItems
{
    public function run()
    {
        try {
            // Some code...
        } catch (ExceptionType1 $e) {
            // Code to handle the exception
        } catch (ExceptionType2 $e) {
            // Code to handle the exception
        } catch (ExceptionType3 $e) {
            // Code to handle the exception
        } finally {
            // nothing
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php71\Rector\TryCatch\MultiExceptionCatchRector\Fixture;

final class MultipleItems
{
    public function run()
    {
        try {
            // Some code...
        } catch (ExceptionType1|ExceptionType2|ExceptionType3 $e) {
            // Code to handle the exception
        } finally {
            // nothing
        }
    }
}

?>
