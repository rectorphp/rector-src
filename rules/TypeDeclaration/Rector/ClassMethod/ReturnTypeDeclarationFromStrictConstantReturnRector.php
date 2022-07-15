<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Attribute\Enterprise;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersion;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\TypeDeclaration\NodeAnalyzer\ReturnTypeAnalyzer\StrictReturnClassConstReturnTypeAnalyzer;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeDeclarationFromStrictConstantReturnRector\ReturnTypeDeclarationFromStrictConstantReturnRectorTest
 */
#[Enterprise]
final class ReturnTypeDeclarationFromStrictConstantReturnRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private StrictReturnClassConstReturnTypeAnalyzer $strictReturnClassConstReturnTypeAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add strict type declaration based on returned constants', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public const NAME = 'name';

    public function run()
    {
        return self::NAME;
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public const NAME = 'name';

    public function run(): string
    {
        return self::NAME;
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
        if ($node->returnType instanceof Node) {
            return null;
        }

        $matchedType = $this->strictReturnClassConstReturnTypeAnalyzer->matchAlwaysReturnConstFetch($node);
        if (! $matchedType instanceof \PHPStan\Type\Type) {
            return null;
        }

        $returnTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($matchedType, TypeKind::RETURN);
        if (! $returnTypeNode instanceof \PhpParser\Node) {
            return null;
        }

        $node->returnType = $returnTypeNode;

        return $node;
    }

    /**
     * @return PhpVersion::*
     */
    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_70;
    }
}
