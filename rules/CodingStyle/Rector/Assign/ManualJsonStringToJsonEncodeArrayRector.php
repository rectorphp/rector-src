<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\Assign;

use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp\Concat as ConcatAssign;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Reflection\ReflectionProvider;
use Rector\CodingStyle\Node\ConcatJoiner;
use Rector\CodingStyle\Node\ConcatManipulator;
use Rector\CodingStyle\NodeFactory\JsonArrayFactory;
use Rector\CodingStyle\NodeFactory\JsonEncodeStaticCallFactory;
use Rector\CodingStyle\ValueObject\ConcatExpressionJoinData;
use Rector\CodingStyle\ValueObject\NodeToRemoveAndConcatItem;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodingStyle\Rector\Assign\ManualJsonStringToJsonEncodeArrayRector\ManualJsonStringToJsonEncodeArrayRectorTest
 */
final class ManualJsonStringToJsonEncodeArrayRector extends AbstractRector
{
    /**
     * @var string
     * @see https://regex101.com/r/85PZHm/1
     */
    private const UNQUOTED_OBJECT_HASH_REGEX = '#(?<start>[^\"])(?<hash>____\w+____)#';

    /**
     * @var string
     * @see https://regex101.com/r/jdJ6n9/1
     */
    private const JSON_STRING_REGEX = '#{(.*?\:.*?)}#s';

    /**
     * @var string
     */
    private const JSON_DATA = 'jsonData';

    public function __construct(
        private ConcatJoiner $concatJoiner,
        private ConcatManipulator $concatManipulator,
        private JsonEncodeStaticCallFactory $jsonEncodeStaticCallFactory,
        private JsonArrayFactory $jsonArrayFactory,
        private ReflectionProvider $reflectionProvider,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Convert manual JSON string to JSON::encode array', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        $someJsonAsString = '{"role_name":"admin","numberz":{"id":"10"}}';
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use Nette\Utils\Json;

final class SomeClass
{
    public function run()
    {
        $data = [
            'role_name' => 'admin',
            'numberz' => ['id' => 10]
        ];
        $someJsonAsString = Json::encode($data);
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
        return [Assign::class];
    }

    /**
     * @param Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->expr instanceof String_) {
            $stringValue = $node->expr->value;

            // A. full json string
            $isJsonString = $this->isJsonString($stringValue);
            if ($isJsonString) {
                $jsonArray = $this->jsonArrayFactory->createFromJsonString($stringValue);
                $jsonEncodeAssign = $this->createJsonEncodeAssign($node->var, $jsonArray);

                $jsonDataVariable = new Variable(self::JSON_DATA);
                $jsonDataAssign = new Assign($jsonDataVariable, $jsonArray);

                $this->addNodeBeforeNode($jsonDataAssign, $node);

                return $jsonEncodeAssign;
            }

            // B. just start of a json? join with all the strings that concat so same variable
            $concatExpressionJoinData = $this->collectContentAndPlaceholderNodesFromNextExpressions($node);

            $stringValue .= $concatExpressionJoinData->getString();

            return $this->removeNodesAndCreateJsonEncodeFromStringValue(
                $concatExpressionJoinData->getNodesToRemove(),
                $stringValue,
                $concatExpressionJoinData->getPlaceholdersToNodes(),
                $node
            );
        }

        if ($node->expr instanceof Concat) {
            // process only first concat
            $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
            if ($parentNode instanceof Concat) {
                return null;
            }

            $concatStringAndPlaceholders = $this->concatJoiner->joinToStringAndPlaceholderNodes($node->expr);

            // B. just start of a json? join with all the strings that concat so same variable
            $concatExpressionJoinData = $this->collectContentAndPlaceholderNodesFromNextExpressions($node);

            $placeholderNodes = array_merge(
                $concatStringAndPlaceholders->getPlaceholderNodes(),
                $concatExpressionJoinData->getPlaceholdersToNodes()
            );

            $stringValue = $concatStringAndPlaceholders->getContent();
            $stringValue .= $concatExpressionJoinData->getString();

            return $this->removeNodesAndCreateJsonEncodeFromStringValue(
                $concatExpressionJoinData->getNodesToRemove(),
                $stringValue,
                $placeholderNodes,
                $node
            );
        }

        return null;
    }

    private function isJsonString(string $stringValue): bool
    {
        if (! (bool) Strings::match($stringValue, self::JSON_STRING_REGEX)) {
            return false;
        }

        try {
            return (bool) Json::decode($stringValue, Json::FORCE_ARRAY);
        } catch (JsonException) {
            return false;
        }
    }

    private function collectContentAndPlaceholderNodesFromNextExpressions(Assign $assign): ConcatExpressionJoinData
    {
        $concatExpressionJoinData = new ConcatExpressionJoinData();

        $currentNode = $assign;

        while ($nextExprAndConcatItem = $this->matchNextExprAssignConcatToSameVariable($assign->var, $currentNode)) {
            $concatItemNode = $nextExprAndConcatItem->getConcatItemNode();

            if ($concatItemNode instanceof String_) {
                $concatExpressionJoinData->addString($concatItemNode->value);
            } elseif ($concatItemNode instanceof Concat) {
                $joinToStringAndPlaceholderNodes = $this->concatJoiner->joinToStringAndPlaceholderNodes(
                    $concatItemNode
                );

                $content = $joinToStringAndPlaceholderNodes->getContent();
                $concatExpressionJoinData->addString($content);

                foreach ($joinToStringAndPlaceholderNodes->getPlaceholderNodes() as $placeholder => $expr) {
                    /** @var string $placeholder */
                    $concatExpressionJoinData->addPlaceholderToNode($placeholder, $expr);
                }
            } elseif ($concatItemNode instanceof Expr) {
                $objectHash = '____' . spl_object_hash($concatItemNode) . '____';

                $concatExpressionJoinData->addString($objectHash);
                $concatExpressionJoinData->addPlaceholderToNode($objectHash, $concatItemNode);
            }

            $concatExpressionJoinData->addNodeToRemove($nextExprAndConcatItem->getRemovedExpr());

            // jump to next one
            $currentNode = $this->getNextExpression($currentNode);
            if (! $currentNode instanceof Node) {
                return $concatExpressionJoinData;
            }
        }

        return $concatExpressionJoinData;
    }

    /**
     * @param Node[] $nodesToRemove
     * @param Expr[] $placeholderNodes
     */
    private function removeNodesAndCreateJsonEncodeFromStringValue(
        array $nodesToRemove,
        string $stringValue,
        array $placeholderNodes,
        Assign $assign
    ): ?Assign {
        $stringValue = Strings::replace($stringValue, self::UNQUOTED_OBJECT_HASH_REGEX, '$1"$2"');
        if (! $this->isJsonString($stringValue)) {
            return null;
        }

        $this->removeNodes($nodesToRemove);

        $jsonArray = $this->jsonArrayFactory->createFromJsonStringAndPlaceholders($stringValue, $placeholderNodes);
        $jsonDataVariable = new Variable(self::JSON_DATA);
        $jsonDataAssign = new Assign($jsonDataVariable, $jsonArray);

        $this->addNodeBeforeNode($jsonDataAssign, $assign);

        return $this->createJsonEncodeAssign($assign->var, $jsonArray);
    }

    private function matchNextExprAssignConcatToSameVariable(
        Expr $expr,
        Assign | ConcatAssign | Expression | Node $currentNode
    ): ?NodeToRemoveAndConcatItem {
        $nextExpression = $this->getNextExpression($currentNode);
        if (! $nextExpression instanceof Expression) {
            return null;
        }

        $nextExpressionNode = $nextExpression->expr;
        if ($nextExpressionNode instanceof ConcatAssign) {
            // is assign to same variable?
            if (! $this->nodeComparator->areNodesEqual($expr, $nextExpressionNode->var)) {
                return null;
            }

            return new NodeToRemoveAndConcatItem($nextExpressionNode, $nextExpressionNode->expr);
        }

        // $value = $value . '...';
        if ($nextExpressionNode instanceof Assign) {
            if (! $nextExpressionNode->expr instanceof Concat) {
                return null;
            }

            // is assign to same variable?
            if (! $this->nodeComparator->areNodesEqual($expr, $nextExpressionNode->var)) {
                return null;
            }

            $firstConcatItem = $this->concatManipulator->getFirstConcatItem($nextExpressionNode->expr);

            // is the first concat the same variable
            if (! $this->nodeComparator->areNodesEqual($expr, $firstConcatItem)) {
                return null;
            }

            // return all but first node
            $allButFirstConcatItem = $this->concatManipulator->removeFirstItemFromConcat($nextExpressionNode->expr);

            return new NodeToRemoveAndConcatItem($nextExpressionNode, $allButFirstConcatItem);
        }

        return null;
    }

    private function getNextExpression(Node $node): ?Node
    {
        $currentExpression = $node->getAttribute(AttributeKey::CURRENT_STATEMENT);
        if (! $currentExpression instanceof Expression) {
            return null;
        }

        return $currentExpression->getAttribute(AttributeKey::NEXT_NODE);
    }

    private function createJsonEncodeAssign(Expr $assignExpr, Array_ $jsonArray): Assign
    {
        if ($this->reflectionProvider->hasClass('Nette\Utils\Json')) {
            return $this->jsonEncodeStaticCallFactory->createFromArray($assignExpr, $jsonArray);
        }

        $jsonDataAssign = new Assign($assignExpr, $jsonArray);
        $jsonDataVariable = new Variable(self::JSON_DATA);
        $jsonDataAssign->expr = $this->nodeFactory->createFuncCall('json_encode', [$jsonDataVariable]);

        return $jsonDataAssign;
    }
}
