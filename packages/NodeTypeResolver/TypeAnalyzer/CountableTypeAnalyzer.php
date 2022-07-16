<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\TypeAnalyzer;

use PhpParser\Node\Expr;
use PHPStan\Type\ObjectType;
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
        private readonly PregMatchTypeCorrector $pregMatchTypeCorrector
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
        $nodeType = $this->pregMatchTypeCorrector->correct($expr, $nodeType);

        foreach ($this->countableObjectTypes as $countableObjectType) {
            if ($countableObjectType->isSuperTypeOf($nodeType)->yes()) {
                return true;
            }
        }

        return $this->arrayTypeAnalyzer->isArrayType($expr);
    }
}
