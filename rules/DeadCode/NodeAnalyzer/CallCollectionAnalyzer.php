<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeAnalyzer;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Type\TypeWithClassName;
use Rector\Enum\ObjectReference;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;

final readonly class CallCollectionAnalyzer
{
    public function __construct(
        private NodeTypeResolver $nodeTypeResolver,
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    /**
     * @param StaticCall[]|MethodCall[] $calls
     */
    public function isExists(array $calls, string $classMethodName, string $className): bool
    {
        foreach ($calls as $call) {
            $callerRoot = $call instanceof StaticCall ? $call->class : $call->var;
            $callerType = $this->nodeTypeResolver->getType($callerRoot);

            if (! $callerType instanceof TypeWithClassName) {
                continue;
            }

            if ($this->isSelfStatic($call) && $this->shouldSkip($call, $classMethodName)) {
                return true;
            }

            if ($callerType->getClassName() !== $className) {
                continue;
            }

            if ($this->shouldSkip($call, $classMethodName)) {
                return true;
            }
        }

        return false;
    }

    private function isSelfStatic(MethodCall|StaticCall|NullsafeMethodCall $call): bool
    {
        return $call instanceof StaticCall && $call->class instanceof Name && in_array(
            $call->class->toString(),
            [ObjectReference::SELF, ObjectReference::STATIC],
            true
        );
    }

    private function shouldSkip(StaticCall|MethodCall|NullsafeMethodCall $call, string $classMethodName): bool
    {
        if (! $call->name instanceof Identifier) {
            return true;
        }

        // the method is used
        return $this->nodeNameResolver->isName($call->name, $classMethodName);
    }
}
