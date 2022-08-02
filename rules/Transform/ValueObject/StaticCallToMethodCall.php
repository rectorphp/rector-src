<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Type\ObjectType;
use Rector\Core\Validation\RectorAssert;

final class StaticCallToMethodCall
{
    /**
     * @param class-string $staticClass
     */
    public function __construct(
        private readonly string $staticClass,
        private readonly string $staticMethod,
        private readonly string $classType,
        private readonly string $methodName
    ) {
        RectorAssert::className($staticClass);

        // special char to match all method names
        if ($staticMethod !== '*') {
            RectorAssert::methodName($staticMethod);
        }

        RectorAssert::className($classType);

        if ($methodName !== '*') {
            RectorAssert::methodName($methodName);
        }
    }

    public function getClassObjectType(): ObjectType
    {
        return new ObjectType($this->classType);
    }

    public function getClassType(): string
    {
        return $this->classType;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function isStaticCallMatch(StaticCall $staticCall): bool
    {
        if (! $staticCall->class instanceof Name) {
            return false;
        }

        $staticCallClassName = $staticCall->class->toString();
        if ($staticCallClassName !== $this->staticClass) {
            return false;
        }

        if (! $staticCall->name instanceof Identifier) {
            return false;
        }

        // all methods
        if ($this->staticMethod === '*') {
            return true;
        }

        $staticCallMethodName = $staticCall->name->toString();
        return $staticCallMethodName === $this->staticMethod;
    }
}
