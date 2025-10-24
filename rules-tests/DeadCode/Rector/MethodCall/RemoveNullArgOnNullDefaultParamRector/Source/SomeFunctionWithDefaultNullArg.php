<?php

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Source;

function SomeFunctionWithDefaultNullArg(?string $someClass = null)
{
}