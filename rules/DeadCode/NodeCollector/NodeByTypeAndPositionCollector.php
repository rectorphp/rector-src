<?php

declare(strict_types=1);

namespace Rector\DeadCode\NodeCollector;

use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use Rector\DeadCode\ValueObject\VariableNodeUse;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeNestingScope\FlowOfControlLocator;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class NodeByTypeAndPositionCollector
{
    public function __construct(
        private readonly FlowOfControlLocator $flowOfControlLocator,
        private readonly NodeNameResolver $nodeNameResolver
    ) {
    }

    /**
     * @param Variable[] $assignedVariables
     * @param Variable[] $assignedVariablesUse
     * @return VariableNodeUse[]
     */
    public function collectNodesByTypeAndPosition(
        array $assignedVariables,
        array $assignedVariablesUse,
        FunctionLike $functionLike
    ): array {
        $nodesByTypeAndPosition = [];

        foreach ($assignedVariables as $assignedVariable) {
            $startTokenPos = $assignedVariable->getStartTokenPos();
<<<<<<< HEAD

            // "-1" is empty value default
=======
>>>>>>> add RAW_VALUE to ide provided constants for attributes
            if ($startTokenPos === -1) {
                continue;
            }

            // not in different scope, than previous one - e.g. if/while/else...
            // get nesting level to $classMethodNode
            /** @var Assign $assign */
            $assign = $assignedVariable->getAttribute(AttributeKey::PARENT_NODE);
            $nestingHash = $this->flowOfControlLocator->resolveNestingHashFromFunctionLike($functionLike, $assign);

            /** @var string $variableName */
            $variableName = $this->nodeNameResolver->getName($assignedVariable);

            $nodesByTypeAndPosition[] = new VariableNodeUse(
                $startTokenPos,
                $variableName,
                VariableNodeUse::TYPE_ASSIGN,
                $assignedVariable,
                $nestingHash
            );
        }

        foreach ($assignedVariablesUse as $assignedVariableUse) {
            $startTokenPos = $assignedVariableUse->getStartTokenPos();
<<<<<<< HEAD

            // "-1" is empty value default
=======
>>>>>>> add RAW_VALUE to ide provided constants for attributes
            if ($startTokenPos === -1) {
                continue;
            }

            /** @var string $variableName */
            $variableName = $this->nodeNameResolver->getName($assignedVariableUse);

            $nodesByTypeAndPosition[] = new VariableNodeUse(
                $startTokenPos,
                $variableName,
                VariableNodeUse::TYPE_USE,
                $assignedVariableUse
            );
        }

        return $this->sortByStart($nodesByTypeAndPosition);
    }

    /**
     * @param VariableNodeUse[] $nodesByTypeAndPosition
     * @return VariableNodeUse[]
     */
    private function sortByStart(array $nodesByTypeAndPosition): array
    {
        usort(
            $nodesByTypeAndPosition,
            fn (VariableNodeUse $firstVariableNodeUse, VariableNodeUse $secondVariableNodeUse): int => $firstVariableNodeUse->getStartTokenPosition() <=> $secondVariableNodeUse->getStartTokenPosition()
        );
        return $nodesByTypeAndPosition;
    }
}
