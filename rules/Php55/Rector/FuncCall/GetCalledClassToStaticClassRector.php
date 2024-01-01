<?php

declare(strict_types=1);

namespace Rector\Php55\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use Rector\Enum\ObjectReference;
use Rector\Rector\AbstractScopeAwareRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://www.php.net/ChangeLog-5.php#5.5.0
 * @changelog https://3v4l.org/dJgXd
 * @see \Rector\Tests\Php55\Rector\FuncCall\GetCalledClassToStaticClassRector\GetCalledClassToStaticClassRectorTest
 */
final class GetCalledClassToStaticClassRector extends AbstractScopeAwareRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change get_called_class() to static::class on non-final class', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
   public function callOnMe()
   {
       var_dump(get_called_class());
   }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
   public function callOnMe()
   {
       var_dump(static::class);
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
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if (! $this->isName($node, 'get_called_class')) {
            return null;
        }

        if (! $scope->isInClass()) {
            return null;
        }

        $classReflection = $scope->getClassReflection();
        if ($classReflection->isAnonymous()) {
            return null;
        }

        if (! $classReflection->isFinal()) {
            return $this->nodeFactory->createClassConstFetch(ObjectReference::STATIC, 'class');
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::CLASSNAME_CONSTANT;
    }
}
