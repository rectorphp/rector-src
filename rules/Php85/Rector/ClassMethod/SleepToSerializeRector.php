<?php

declare(strict_types=1);

namespace Rector\Php85\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Rector\AbstractRector;
use Rector\TypeDeclaration\NodeAnalyzer\ReturnAnalyzer;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_the_sleep_and_wakeup_magic_methods
 * @see \Rector\Tests\Php85\Rector\MethodCall\SleepToSerializeRector\SleepToSerializeRectorTest
 */
final class SleepToSerializeRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private ReturnAnalyzer $returnAnalyzer
    ) {
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::DEPRECATED_METHOD_SLEEP;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change __sleep() to __serialize() with correct return values',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class User {
    private $id;
    private $name;

    public function __sleep() {
        return ['id', 'name'];
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class User {
    private $id;
    private $name;

    public function __serialize(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
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
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node->name, '__sleep')) {
            return null;
        }

        if ($node->returnType instanceof Identifier && $this->isName($node->returnType, 'array')) { 
            return null;
        }

        $returns = $this->betterNodeFinder->findReturnsScoped($node);
        if (! $this->returnAnalyzer->hasOnlyReturnWithExpr($node, $returns)) {
            return null;
        }
        
        $hasChanged = false;
        foreach ($returns as $return) {
            if (! $return->expr instanceof Array_ ) {
                return null;
            }

            if (count($return->expr->items) > 0) {
                $newItems = [];
                foreach ($return->expr->items as $item) {
                    if ($item !== null && $item->value instanceof Node\Scalar\String_) {
                        $propName = $item->value->value;
                        $newItems[] = new ArrayItem(
                            new PropertyFetch(new Node\Expr\Variable('this'), $propName),
                            $item->value
                        );
                    }
                }
                if (count($newItems) > 0) {
                    $hasChanged = true;
                    $return->expr->items = $newItems;
                }
            }
        }

        if($hasChanged){
            $node->name = new Identifier('__serialize');
            $node->returnType = new Identifier('array');
            return $node;
        }

        return null;
    }
}
