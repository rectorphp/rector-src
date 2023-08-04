<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\Php\PhpVersionProvider;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\FollowParentReturnTypeDeclarationRector\FollowParentReturnTypeDeclarationRectorTest
 */
final class FollowParentReturnTypeDeclarationRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(private readonly PhpVersionProvider $phpVersionProvider)
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Copy return type declaration from parent method', [
            new CodeSample(
                <<<'CODE_SAMPLE'
abstract class ParentClass
{
    public abstract function getData(): array;
}

class ChildClass extends ParentClass
{
    public function getData()
    {
        return [];
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
abstract class ParentClass
{
    public abstract function getData(): array;
}

class ChildClass extends ParentClass
{
    public function getData(): array
    {
        return [];
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
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::SCALAR_TYPES;
    }
}
