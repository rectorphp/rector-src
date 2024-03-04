<?php

declare(strict_types=1);

namespace Rector\Config\Level;

use Rector\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector;
use Rector\Contract\Rector\RectorInterface;
use Rector\DeadCode\Rector\Array_\RemoveDuplicatedArrayKeyRector;
use Rector\DeadCode\Rector\Assign\RemoveDoubleAssignRector;
use Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;
use Rector\DeadCode\Rector\BooleanAnd\RemoveAndTrueRector;
use Rector\DeadCode\Rector\Cast\RecastingRemovalRector;
use Rector\DeadCode\Rector\ClassConst\RemoveUnusedPrivateClassConstantRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnExprInConstructRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\DeadCode\Rector\Concat\RemoveConcatAutocastRector;
use Rector\DeadCode\Rector\ConstFetch\RemovePhpVersionIdCheckRector;
use Rector\DeadCode\Rector\Expression\RemoveDeadStmtRector;
use Rector\DeadCode\Rector\Expression\SimplifyMirrorAssignRector;
use Rector\DeadCode\Rector\For_\RemoveDeadContinueRector;
use Rector\DeadCode\Rector\For_\RemoveDeadIfForeachForRector;
use Rector\DeadCode\Rector\For_\RemoveDeadLoopRector;
use Rector\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector;
use Rector\DeadCode\Rector\FunctionLike\RemoveDeadReturnRector;
use Rector\DeadCode\Rector\If_\ReduceAlwaysFalseIfOrRector;
use Rector\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector;
use Rector\DeadCode\Rector\If_\RemoveDeadInstanceOfRector;
use Rector\DeadCode\Rector\If_\RemoveTypedPropertyDeadInstanceOfRector;
use Rector\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector;
use Rector\DeadCode\Rector\If_\SimplifyIfElseWithSameContentRector;
use Rector\DeadCode\Rector\If_\UnwrapFutureCompatibleIfPhpVersionRector;
use Rector\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector;
use Rector\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector;
use Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector;
use Rector\DeadCode\Rector\Property\RemoveUselessVarTagRector;
use Rector\DeadCode\Rector\PropertyProperty\RemoveNullPropertyInitializationRector;
use Rector\DeadCode\Rector\Return_\RemoveDeadConditionAboveReturnRector;
use Rector\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector;
use Rector\DeadCode\Rector\Stmt\RemoveUnreachableStatementRector;
use Rector\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector;
use Rector\DeadCode\Rector\Ternary\TernaryToBooleanOrFalseToBooleanAndRector;
use Rector\DeadCode\Rector\TryCatch\RemoveDeadTryCatchRector;

/**
 * Use the index to find which rules are applied for each withDeadCodeLevel() level
 *
 * Start at 0, go slowly higher, one level per PR, and improve your rule coverage
 *
 * From the safest rules to more changing ones.
 *
 * @experimental Since 0.19.7 This list can change in time, based on community feedback,
 * what rules are safer than other. The safest rules will be always in the top.
 */
final class DeadCodeLevel
{
    /**
     * Mind that return type declarations are the safest to add,
     * followed by property, then params
     *
     * @var array<class-string<RectorInterface>>
     */
    public const RULES = [
        // easy picks
        0 => RemoveUnusedForeachKeyRector::class,
        1 => RemoveDuplicatedArrayKeyRector::class,
        2 => RecastingRemovalRector::class,
        3 => RemoveAndTrueRector::class,
        4 => SimplifyMirrorAssignRector::class,
        5 => RemoveDeadContinueRector::class,
        6 => RemoveUnusedNonEmptyArrayBeforeForeachRector::class,
        7 => RemoveNullPropertyInitializationRector::class,
        8 => RemoveUselessReturnExprInConstructRector::class,

        9 => RemoveTypedPropertyDeadInstanceOfRector::class,
        10 => TernaryToBooleanOrFalseToBooleanAndRector::class,
        11 => RemoveDoubleAssignRector::class,
        12 => RemoveConcatAutocastRector::class,
        13 => SimplifyIfElseWithSameContentRector::class,
        14 => SimplifyUselessVariableRector::class,
        15 => RemoveDeadZeroAndOneOperationRector::class,

        // docblock
        16 => RemoveUselessParamTagRector::class,
        17 => RemoveUselessReturnTagRector::class,
        18 => RemoveNonExistingVarAnnotationRector::class,
        19 => RemoveUselessVarTagRector::class,
        20 => RemovePhpVersionIdCheckRector::class,

        21 => RemoveAlwaysTrueIfConditionRector::class,
        22 => ReduceAlwaysFalseIfOrRector::class,
        23 => RemoveUnusedPrivateClassConstantRector::class,
        24 => RemoveUnusedPrivatePropertyRector::class,

        25 => RemoveDuplicatedCaseInSwitchRector::class,
        26 => RemoveDeadInstanceOfRector::class,

        27 => RemoveDeadTryCatchRector::class,
        28 => RemoveDeadIfForeachForRector::class,
        29 => RemoveDeadStmtRector::class,
        30 => UnwrapFutureCompatibleIfPhpVersionRector::class,
        31 => RemoveParentCallWithoutParentRector::class,
        32 => RemoveDeadConditionAboveReturnRector::class,
        33 => RemoveDeadLoopRector::class,

        // removing methods could be risky if there is some magic loading them
        34 => RemoveUnusedPromotedPropertyRector::class,
        35 => RemoveUnusedPrivateMethodParameterRector::class,
        36 => RemoveUnusedPrivateMethodRector::class,
        37 => RemoveUnreachableStatementRector::class,
        38 => RemoveUnusedVariableAssignRector::class,

        // this could break framework magic autowiring in some cases
        39 => RemoveUnusedConstructorParamRector::class,
        40 => RemoveEmptyClassMethodRector::class,
        41 => RemoveDeadReturnRector::class,
    ];
}
