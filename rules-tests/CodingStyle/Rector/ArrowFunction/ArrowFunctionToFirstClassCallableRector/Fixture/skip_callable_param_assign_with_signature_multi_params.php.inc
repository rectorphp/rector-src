<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionToFirstClassCallableRector\Fixture;

class SkipCallableParamAssignWithSignatureMultiParams
{
    private $transform;

    /**
     * @param callable(mixed $datum, int|string|null $key): mixed $callable
     */
    public function withTransform(callable $callable)
    {
        $this->transform = $callable;
        return $this;
    }

    public function run($value, $optionalData = [])
    {
        var_dump(($this->transform)($value, $optionalData));
    }
}

(new SkipCallableParamAssignWithSignatureMultiParams())
    ->withTransform(fn (string $datum): string => trim($datum))
    ->run('  some data  ');


?>
