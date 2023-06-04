<?php

namespace Rector\Tests\Php71\Rector\TryCatch\MultiExceptionCatchRector\Fixture;

final class MergeCommentedSame
{
    public function run()
    {
        try {
            // Some code...
        } catch (ExceptionType1 $e) {
            // Code to handle the exception
        } catch (ExceptionType2 $e) {
            // Code to handle the exception
        } catch (Exception $e) {
            // ...
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php71\Rector\TryCatch\MultiExceptionCatchRector\Fixture;

final class MergeCommentedSame
{
    public function run()
    {
        try {
            // Some code...
        } catch (ExceptionType1|ExceptionType2 $e) {
            // Code to handle the exception
        } catch (Exception $e) {
            // ...
        }
    }
}

?>
