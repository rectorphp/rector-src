<?php

declare(strict_types=1);

namespace Rector\Php55\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Scalar\MagicConst\Class_;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @extends AbstractRector<Class_>
 * @see \Rector\Tests\Php55\Rector\Class_\ClassConstantToSelfClassRector\ClassConstantToSelfClassRectorTest
 */
final class ClassConstantToSelfClassRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change `__CLASS__` to `self::class`', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
   public function callOnMe()
   {
       var_dump(__CLASS__);
   }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
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

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function refactor(Node $node): ClassConstFetch
    {
        return $this->nodeFactory->createSelfFetchConstant('class');
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::CLASSNAME_CONSTANT;
    }
}
