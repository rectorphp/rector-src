<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\UnionType;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclarationDocblocks\NodeFinder\ArrayDimFetchFinder;
use Rector\VendorLocker\ParentClassMethodTypeOverrideGuard;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamFromDimFetchKeyUseRector\AddParamFromDimFetchKeyUseRectorTest
 */
final class AddParamFromDimFetchKeyUseRector extends AbstractRector
{
    public function __construct(
        private readonly ArrayDimFetchFinder $arrayDimFetchFinder,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly ParentClassMethodTypeOverrideGuard $parentClassMethodTypeOverrideGuard
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add method param type based on use in array dim fetch of known keys',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    public function get($key)
    {
        $data = [
            'name' => 'John',
            'age' => 30,
        ];

        return $data[$key];
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    public function get(string $key)
    {
        $data = [
            'name' => 'John',
            'age' => 30,
        ];

        return $data[$key];
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
        $hasChanged = false;

        foreach ($node->getMethods() as $classMethod) {
            if ($classMethod->params === []) {
                continue;
            }

            if ($this->parentClassMethodTypeOverrideGuard->hasParentClassMethod($classMethod)) {
                continue;
            }

            foreach ($classMethod->getParams() as $param) {
                if ($param->type instanceof Node) {
                    continue;
                }

                /** @var string $paramName */
                $paramName = $this->getName($param->var);

                $dimFetches = $this->arrayDimFetchFinder->findByDimName($classMethod, $paramName);
                if ($dimFetches === []) {
                    continue;
                }

                foreach ($dimFetches as $dimFetch) {
                    $dimFetchType = $this->getType($dimFetch->var);

                    if (! $dimFetchType instanceof ArrayType && ! $dimFetchType instanceof ConstantArrayType) {
                        continue;
                    }

                    if ($dimFetch->dim instanceof Variable) {
                        $type = $this->nodeTypeResolver->getType($dimFetch->dim);
                        if ($type instanceof UnionType) {
                            continue;
                        }
                    }

                    $paramTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
                        $dimFetchType->getKeyType(),
                        TypeKind::PARAM
                    );

                    if (! $paramTypeNode instanceof Node) {
                        continue;
                    }

                    $param->type = $paramTypeNode;
                    $hasChanged = true;
                }
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }
}
