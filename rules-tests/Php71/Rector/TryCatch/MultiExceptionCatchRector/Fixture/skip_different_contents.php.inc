<?php

namespace Rector\Tests\Php71\Rector\TryCatch\MultiExceptionCatchRector\Fixture;

final class SkipDifferentContents
{
    public function run()
    {
        try {
            // Some code...
        } catch (ExceptionType1 $e) {
            // Code to handle the exception
        } catch (ExceptionType2 $e) {
            $differentContent = 'hey';
        }
    }
}
