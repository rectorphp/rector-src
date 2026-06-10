<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector\Fixture;

/**
 * @see https://3v4l.org/RCVaU#v8.3.9 vs https://3v4l.org/6DSlE#v8.3.9
 */
final class SkipWithUseOfFuncNumArgs
{
    private $b = 1;

    public function set($a, $b = null)
    {
        if (func_num_args() > 1) {
            $this->b = $b;
        }
    }

    public function get()
    {
        $this->set(1, null);
        return $this->b;
    }
}
