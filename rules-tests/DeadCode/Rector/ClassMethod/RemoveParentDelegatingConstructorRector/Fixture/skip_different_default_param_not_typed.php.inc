<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Fixture;

use Exception;

class SkipDifferentDefaultParamNoTyped extends Exception
{
    public function __construct($message = 'Email not sent.', ...$args)
    {
        parent::__construct($message, ...$args);
    }
}
