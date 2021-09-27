<?php

declare(strict_types=1);

namespace Rector\CodingStyle\NodeFactory;

use Nette\Utils\Json;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;
use Rector\CodingStyle\NodeAnalyzer\ImplodeAnalyzer;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\Node\NodeFactory;
use Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser;

final class JsonArrayFactory
{
    public function __construct(
        private NodeFactory $nodeFactory,
        private ImplodeAnalyzer $implodeAnalyzer,
        private SimpleCallableNodeTraverser $simpleCallableNodeTraverser
    ) {
    }

    public function createFromJsonString(string $stringValue): Array_
    {
        $array = Json::decode($stringValue, Json::FORCE_ARRAY);
        return $this->nodeFactory->createArray($array);
    }

    /**
     * @param Expr[] $placeholderNodes
     */
    public function createFromJsonStringAndPlaceholders(string $jsonString, array $placeholderNodes): Array_
    {
        $jsonArray = $this->createFromJsonString($jsonString);
        $this->replaceNodeObjectHashPlaceholdersWithNodes($jsonArray, $placeholderNodes);

        return $jsonArray;
    }

    /**
     * @param Expr[] $placeholderNodes
     */
    private function replaceNodeObjectHashPlaceholdersWithNodes(Array_ $array, array $placeholderNodes): void
    {
        // traverse and replace placeholder by original nodes
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($array, function (Node $node) use (
            $placeholderNodes
        ): ?Expr {
            if ($node instanceof Array_ && count($node->items) === 1) {
                $onlyItem = $node->items[0];
                if (! $onlyItem instanceof ArrayItem) {
                    throw new ShouldNotHappenException();
                }

                $placeholderNode = $this->matchPlaceholderNode($onlyItem->value, $placeholderNodes);

                if ($placeholderNode && $this->implodeAnalyzer->isImplodeToJson($placeholderNode)) {
                    /**
                     * @var FuncCall $placeholderNode
                     * @var Arg $firstArg
                     *
                     * Arg check already on $this->implodeAnalyzer->isImplodeToJson() above
                     */
                    $firstArg = $placeholderNode->args[1];
                    return $firstArg->value;
                }
            }

            return $this->matchPlaceholderNode($node, $placeholderNodes);
        });
    }

    /**
     * @param Expr[] $placeholderNodes
     */
    private function matchPlaceholderNode(Node $node, array $placeholderNodes): ?Expr
    {
        if (! $node instanceof String_) {
            return null;
        }

        return $placeholderNodes[$node->value] ?? null;
    }
}
