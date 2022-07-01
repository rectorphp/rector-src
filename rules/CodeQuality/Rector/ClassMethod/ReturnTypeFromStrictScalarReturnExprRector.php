<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\ClassMethod;

use PhpParser\Node;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
>>>>>>> 6ae4aba26d... fixup! [ci-review] Rector Rectify
=======
use PhpParser\Node\Stmt\ClassMethod;
>>>>>>> a5357dfa76... [ci-review] Rector Rectify
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersion;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector\ReturnTypeFromStrictScalarReturnExprRectorTest
 */
final class ReturnTypeFromStrictScalarReturnExprRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change return type based on strict scalar returns - string, int, float or bool', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run($value)
    {
        if ($value) {
            return 'yes';
        }

        return 'no';
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run($value): string
    {
        if ($value) {
            return 'yes';
        }

        return 'no';
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
<<<<<<< HEAD
<<<<<<< HEAD
        return [\PhpParser\Node\Stmt\ClassMethod::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\ClassMethod $node
=======
        return [ClassMethod::class, Function_::class, Closure::class];
    }

    /**
     * @param ClassMethod|Function_|Closure $node
>>>>>>> 6ae4aba26d... fixup! [ci-review] Rector Rectify
=======
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
>>>>>>> a5357dfa76... [ci-review] Rector Rectify
     */
    public function refactor(Node $node): ClassMethod
    {
        if ($node->returnType !== null) {
            return null;
        }

        dump(111);
        die;

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_70;
    }
}
