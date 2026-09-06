<?php

namespace Rector\Tests\DeadCode\Rector\TryCatch\RemoveDeadCatchRector\Fixture;

class SkipDeadCatchBeforeCatchAllException
{
    public function run()
    {
        try {
            // some code
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (OAuthServerException $exception) {
            echo 'oauth';
        } catch (\Exception $exception) {
            echo 'general';
        }
    }
}

?>
