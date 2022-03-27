<?php

declare(strict_types=1);

namespace Rector\Php74\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\Enum\ObjectReference;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/deprecations_php_7_4 (not confirmed yet)
 * @see https://3v4l.org/GU9dP
 * @see \Rector\Tests\Php74\Rector\FuncCall\GetCalledClassToSelfClassRector\GetCalledClassToSelfClassRectorTest
 */
final class GetCalledClassToSelfClassRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(private readonly ClassAnalyzer $classAnalyzer)
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change get_called_class() to self::class on final class', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
   public function callOnMe()
   {
       var_dump(get_called_class());
   }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
   public function callOnMe()
   {
       var_dump(self::class);
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
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node, 'get_called_class')) {
            return null;
        }

        $class = $this->betterNodeFinder->findParentType($node, Class_::class);

        if (! $class instanceof Class_) {
            return null;
        }
        if ($class->isFinal()) {
            return $this->nodeFactory->createClassConstFetch(ObjectReference::SELF(), 'class');
        }
        if ($this->classAnalyzer->isAnonymousClass($class)) {
            return $this->nodeFactory->createClassConstFetch(ObjectReference::SELF(), 'class');
        }
        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::CLASSNAME_CONSTANT;
    }
}
