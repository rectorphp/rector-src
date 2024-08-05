<?php

namespace Rector\Tests\Php71\Rector\TryCatch\MultiExceptionCatchRector\Fixture;

final class SkipThrowSameDifferentContent
{
    public function run()
    {
        try {
            // some
        } catch (\RuntimeException $e) {
            throw new \Exception();
        } catch (\TypeError $e) {
            echo 123;

            throw new \Exception();
        } catch (\Error $e) {
            throw new \Exception();
        }
    }
}
