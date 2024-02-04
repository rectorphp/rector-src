<?php

declare(strict_types=1);

namespace Rector\Transform\Rector\FileWithoutNamespace;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\Exception\ShouldNotHappenException;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Transform\Rector\FileWithoutNamespace\RectorConfigBuilderRector\RectorConfigBuilderTest
 */
final class RectorConfigBuilderRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change RectorConfig to RectorConfigBuilder', [
            new CodeSample(
                <<<'CODE_SAMPLE'
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(SomeRector::class);
};
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
return RectorConfig::configure()->rules([SomeRector::class]);
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FileWithoutNamespace::class];
    }

    /**
     * @param FileWithoutNamespace $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($node->stmts as $stmt) {
            if (! $stmt instanceof Return_) {
                continue;
            }

            if (! $stmt->expr instanceof Closure) {
                continue;
            }

            if (count($stmt->expr->params) !== 1) {
                continue;
            }

            $param = $stmt->expr->params[0];
            if (! $param->type instanceof FullyQualified) {
                continue;
            }

            if ($param->type->toString() !== 'Rector\Config\RectorConfig') {
                continue;
            }

            $stmts = $stmt->expr->stmts;
            if ($stmts === []) {
                throw new ShouldNotHappenException('RectorConfig must have at least 1 stmts');
            }

            $newExpr = new StaticCall(new FullyQualified('Rector\Config\RectorConfig'), 'configure');

            $rules = [];
            foreach ($stmts as $rectorConfigStmt) {
                // complex stmts should be skipped, eg: with if else
                if (! $rectorConfigStmt instanceof Expression) {
                    return null;
                }

                // debugging or variable init? skip
                if (! $rectorConfigStmt->expr instanceof MethodCall) {
                    return null;
                }

                // another method call? skip
                if (! $this->isObjectType($rectorConfigStmt->expr->var, new ObjectType('Rector\Config\RectorConfig'))) {
                    return null;
                }

                if ($this->isName($rectorConfigStmt->expr->name, 'rule')) {
                    $rules[] = $rectorConfigStmt->expr->args[0]->value;
                }
            }

            if ($rules !== []) {
                $stmt->expr = $this->nodeFactory->createMethodCall($newExpr, 'withRules', $rules);
            }
        }

        return null;
    }
}
