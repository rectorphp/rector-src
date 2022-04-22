<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\TypeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassLike;
use PHPStan\Type\ObjectType;
use Rector\Core\NodeAnalyzer\PropertyFetchAnalyzer;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeTypeResolver\NodeTypeCorrector\PregMatchTypeCorrector;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class CountableTypeAnalyzer
{
    /**
     * @var ObjectType[]
     */
    private array $countableObjectTypes = [];

    public function __construct(
        private readonly ArrayTypeAnalyzer $arrayTypeAnalyzer,
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly PregMatchTypeCorrector $pregMatchTypeCorrector,
        private readonly PropertyFetchAnalyzer $propertyFetchAnalyzer,
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
        $this->countableObjectTypes = [
            new ObjectType('Countable'),
            new ObjectType('SimpleXMLElement'),
            new ObjectType('ResourceBundle'),
        ];
    }

    public function isCountableType(Node $node): bool
    {
        if ($this->propertyFetchAnalyzer->isPropertyFetch($node)) {
            $type = $node instanceof PropertyFetch
                ? $this->nodeTypeResolver->getType($node->var)
                : $this->nodeTypeResolver->getType($node->class);

            $classLike = $this->betterNodeFinder->findParentType($node, ClassLike::class);
            if (! $classLike instanceof ClassLike && ! $type instanceof \PHPStan\Type\ObjectType) {
                return true;
            }
        }

        if ($node instanceof Variable) {
            $type = $this->nodeTypeResolver->getType($node);

            $functionLike = $this->betterNodeFinder->findParentType($node, FunctionLike::class);
            if (! $functionLike instanceof FunctionLike && ! $type instanceof \PHPStan\Type\ObjectType) {
                return true;
            }
        }

        $nodeType = $this->nodeTypeResolver->getType($node);
        $nodeType = $this->pregMatchTypeCorrector->correct($node, $nodeType);

        foreach ($this->countableObjectTypes as $countableObjectType) {
            if ($countableObjectType->isSuperTypeOf($nodeType)->yes()) {
                return true;
            }
        }

        return $this->arrayTypeAnalyzer->isArrayType($node);
    }
}
