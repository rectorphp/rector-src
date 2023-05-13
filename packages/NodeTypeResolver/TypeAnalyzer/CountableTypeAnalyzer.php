<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\TypeAnalyzer;

use PhpParser\Node\Expr;
use PHPStan\Type\ObjectType;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class CountableTypeAnalyzer
{
    /**
     * @var ObjectType[]
     */
    private array $countableObjectTypes = [];

    public function __construct(
        private readonly ArrayTypeAnalyzer $arrayTypeAnalyzer,
        private readonly NodeTypeResolver $nodeTypeResolver
    ) {
        $this->countableObjectTypes = [
            new ObjectType('Countable'),
            new ObjectType('SimpleXMLElement'),
            new ObjectType('ResourceBundle'),
        ];
    }

    public function isCountableType(Expr $expr): bool
    {
        $nodeType = $this->nodeTypeResolver->getType($expr);

        foreach ($this->countableObjectTypes as $countableObjectType) {
            if ($countableObjectType->isSuperTypeOf($nodeType)->yes()) {
                return true;
            }
        }

        return $this->arrayTypeAnalyzer->isArrayType($expr);
    }
}
