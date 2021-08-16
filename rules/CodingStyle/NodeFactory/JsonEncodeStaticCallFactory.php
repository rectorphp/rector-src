<?php

declare(strict_types=1);

namespace Rector\CodingStyle\NodeFactory;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\PhpParser\Node\NodeFactory;

/**
 * Creates + adds
 *
 * $jsonData = ['...'];
 * $json = Nette\Utils\Json::encode($jsonData);
 */
final class JsonEncodeStaticCallFactory
{
    public function __construct(
        private NodeFactory $nodeFactory,
        private ReflectionProvider $reflectionProvider,
    ) {
    }

    /**
     * Creates + adds
     *
     * $jsonData = ['...'];
     * $json = Nette\Utils\Json::encode($jsonData);
     */
    public function createFromArray(Expr $assignExpr, Array_ $jsonArray): Assign
    {
        $jsonDataAssign = new Assign($assignExpr, $jsonArray);

        $jsonDataVariable = new Variable('jsonData');
        if ($this->reflectionProvider->hasClass('Nette\Utils\Json')) {
            $jsonDataAssign->expr = $this->nodeFactory->createStaticCall('Nette\Utils\Json', 'encode', [$jsonDataVariable]);
        } else {
            $jsonDataAssign->expr = $this->nodeFactory->createFuncCall('json_encode', [$jsonDataVariable]);
        }

        return $jsonDataAssign;
    }
}
