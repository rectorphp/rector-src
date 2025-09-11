<?php

declare(strict_types=1);

namespace Rector\Php85\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_the_sleep_and_wakeup_magic_methods
 * @see \Rector\Tests\Php85\Rector\Class_\WakeupToUnserializeRector\WakeupToUnserializeRectorTest
 */
final class WakeupToUnserializeRector extends AbstractRector implements MinPhpVersionInterface
{
    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::DEPRECATED_METHOD_WAKEUP;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change __wakeup() to __unserialize()',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class User {
    public function __wakeup() {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class User {
    public function __unserialize(){
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->getMethod('__unserialize') instanceof ClassMethod) {
            return null;
        }

        $classMethod = $node->getMethod('__wakeup');
        if (! $classMethod instanceof ClassMethod) {
            return null;
        }
         
        $classMethod->name = new Identifier('__unserialize');
        $classMethod->returnType = new Identifier('void');
        return $node;
    }
}
