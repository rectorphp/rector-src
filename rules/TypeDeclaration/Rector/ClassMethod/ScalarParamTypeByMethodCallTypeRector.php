<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use Rector\Configuration\Deprecation\Contract\DeprecatedInterface;
use Rector\Doc\CodeSample\CodeSample;
use Rector\Doc\RuleDefinition;

/**
 * @deprecated This rule is deprecated, as it fills invalid types and can cause type errors on controllers and other input calls. Inferring param types from a single caller is unreliable and needs human verification, so this better suits static analysis.
 */
final class ScalarParamTypeByMethodCallTypeRector extends AbstractParamTypeByMethodCallTypeRector implements DeprecatedInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change scalar param type based on passed method call type', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeTypedService
{
    public function run(int $value)
    {
    }
}

final class UseDependency
{
    public function __construct(
        private SomeTypedService $someTypedService
    ) {
    }

    public function go($value)
    {
        $this->someTypedService->run($value);
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeTypedService
{
    public function run(int $value)
    {
    }
}

final class UseDependency
{
    public function __construct(
        private SomeTypedService $someTypedService
    ) {
    }

    public function go(int $value)
    {
        $this->someTypedService->run($value);
    }
}
CODE_SAMPLE
            ),
        ]);
    }
}
