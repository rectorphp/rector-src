<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\ClassConstFetch;

use Exception;
use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\Error;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\ClassConstFetch\ConvertStaticPrivateConstantToSelfRector\ConvertStaticPrivateConstantToSelfRectorTest
 * @see https://3v4l.org/8Y0ba
 * @see https://phpstan.org/r/11d4c850-1a40-4fae-b665-291f96104d11
 */
final class ConvertStaticPrivateConstantToSelfRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replaces static::* access to private constants with self::*',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class Foo {
    private const BAR = 'bar';
    public function run()
    {
        $bar = static::BAR;
    }
}
CODE_SAMPLE
,
                    <<<'CODE_SAMPLE'
class Foo {
    private const BAR = 'bar';
    public function run()
    {
        $bar = self::BAR;
    }
}
CODE_SAMPLE
,
                ),
            ],
        );
    }

    public function getNodeTypes(): array
    {
        return [ClassConstFetch::class];
    }

    /**
     * @param \PhpParser\Node\Expr\ClassConstFetch $node
     *
     * @return \PhpParser\Node|null
     */
    public function refactor(Node $node)
    {
        if (! $this->isUsingStatic($node)) {
            return null;
        }

        if (! $this->isPrivateConstant($node)) {
            return null;
        }

        return new ClassConstFetch(new Name('self'), $node->name,);
    }

    private function isUsingStatic(ClassConstFetch $node): bool
    {
        if (! $node->class instanceof Name) {
            return false;
        }

        return $node->class->getFirst() === 'static';
    }

    private function isPrivateConstant(ClassConstFetch $node): bool
    {
        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return false;
        }
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }
        $constantName = $node->name;
        if ($constantName instanceof Error) {
            return false;
        }
        try {
            $constantReflection = $classReflection->getConstant($constantName->toString());
        } catch (Exception $e) {
            return false;
        }

        return $constantReflection->isPrivate();
    }
}
