<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ArrowFunction;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PHPStan\Analyser\Scope;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\AddArrowFunctionReturnTypeRectorTest
 */
final class AddArrowFunctionReturnTypeRector extends AbstractScopeAwareRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add known return type to arrow function', [
            new CodeSample(
                <<<'CODE_SAMPLE'
fn () => [];
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
fn (): array => [];
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ArrowFunction::class];
    }

    /**
     * @param ArrowFunction $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if ($node->returnType !== null) {
            return null;
        }

        $type = $this->getType($node->expr);
        $returnTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($type, TypeKind::RETURN);

        if ($returnTypeNode === null) {
            return null;
        }

        $node->returnType = $returnTypeNode;

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::SCALAR_TYPES;
    }
}
