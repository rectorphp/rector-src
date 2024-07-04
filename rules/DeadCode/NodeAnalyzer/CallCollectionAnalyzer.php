<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeAnalyzer;

use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Type\MixedType;
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
     * @param StaticCall[]|MethodCall[]|NullsafeMethodCall[] $calls
     */
    public function isExists(array $calls, string $classMethodName, string $className): bool
    {
        foreach ($calls as $call) {
            $callerRoot = $call instanceof StaticCall ? $call->class : $call->var;
            $callerType = $this->nodeTypeResolver->getType($callerRoot);

            if (! $callerType instanceof TypeWithClassName) {
                // handle fluent by $this->bar()->baz()->qux()
                // that methods don't have return type
                if ($callerType instanceof MixedType && ! $callerType->isExplicitMixed()) {
                    $cloneCallerRoot = clone $callerRoot;
                    $isFluent = false;
                    // init
                    $methodCallNames = [];

                    // first append
                    $methodCallNames[] = (string) $this->nodeNameResolver->getName($call->name);
                    while ($cloneCallerRoot instanceof MethodCall) {
                        $methodCallNames[] = (string) $this->nodeNameResolver->getName($cloneCallerRoot->name);
                        if ($cloneCallerRoot->var instanceof Variable && $cloneCallerRoot->var->name === 'this') {
                            $isFluent = true;
                            break;
                        }

                        $cloneCallerRoot = $cloneCallerRoot->var;
                    }

                    if ($isFluent && in_array($classMethodName, $methodCallNames, true)) {
                        return true;
                    }
                }

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
