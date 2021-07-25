<?php

declare(strict_types=1);

namespace Rector\Naming\Guard;

use DateTimeInterface;
use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeWithClassName;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Naming\Naming\ConflictingNameResolver;
use Rector\Naming\Naming\OverridenExistingNamesResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PHPStanStaticTypeMapper\Utils\TypeUnwrapper;

/**
 * This class check if a variable name change breaks existing code in class method
 */
final class BreakingVariableRenameGuard
{
    /**
     * @var string
     * @see https://regex101.com/r/1pKLgf/1
     */
    private const AT_NAMING_REGEX = '#[\w+]At$#';

    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private ConflictingNameResolver $conflictingNameResolver,
        private NodeTypeResolver $nodeTypeResolver,
        private OverridenExistingNamesResolver $overridenExistingNamesResolver,
        private TypeUnwrapper $typeUnwrapper,
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    public function shouldSkipVariable(
        string $currentName,
        string $expectedName,
        ClassMethod | Function_ | Closure $functionLike,
        Variable $variable
    ): bool {
        // is the suffix? → also accepted
        $expectedNameCamelCase = ucfirst($expectedName);
        if (\str_ends_with($currentName, $expectedNameCamelCase)) {
            return true;
        }

        if ($this->conflictingNameResolver->hasNameIsInFunctionLike($expectedName, $functionLike)) {
            return true;
        }

        if ($this->overridenExistingNamesResolver->hasNameInClassMethodForNew($currentName, $functionLike)) {
            return true;
        }

        if ($this->isVariableAlreadyDefined($variable, $currentName)) {
            return true;
        }

        if ($this->hasConflictVariable($functionLike, $expectedName)) {
            return true;
        }

        if ($this->isUsedInClosureUsesName($expectedName, $functionLike)) {
            return true;
        }

        if ($this->isUsedInForeachKeyValueVar($variable, $currentName)) {
            return true;
        }

        return $this->isUsedInIfAndOtherBranches($variable, $currentName);
    }

    public function shouldSkipParam(
        string $currentName,
        string $expectedName,
        ClassMethod $classMethod,
        Param $param
    ): bool {
        // is the suffix? → also accepted
        $expectedNameCamelCase = ucfirst($expectedName);
        if (\str_ends_with($currentName, $expectedNameCamelCase)) {
            return true;
        }

        $conflictingNames = $this->conflictingNameResolver->resolveConflictingVariableNamesForParam($classMethod);
        if (in_array($expectedName, $conflictingNames, true)) {
            return true;
        }

        if ($this->conflictingNameResolver->hasNameIsInFunctionLike($expectedName, $classMethod)) {
            return true;
        }

        if ($this->overridenExistingNamesResolver->hasNameInClassMethodForParam($expectedName, $classMethod)) {
            return true;
        }

        if ($this->isVariableAlreadyDefined($param->var, $currentName)) {
            return true;
        }

        if ($this->isRamseyUuidInterface($param)) {
            return true;
        }

        if ($this->isDateTimeAtNamingConvention($param)) {
            return true;
        }

        return (bool) $this->betterNodeFinder->find((array) $classMethod->stmts, function (Node $node) use (
            $expectedName
        ): bool {
            if (! $node instanceof Variable) {
                return false;
            }

            return $this->nodeNameResolver->isName($node, $expectedName);
        });
    }

    private function isVariableAlreadyDefined(Variable $variable, string $currentVariableName): bool
    {
        $scope = $variable->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return false;
        }

        $trinaryLogic = $scope->hasVariableType($currentVariableName);
        if ($trinaryLogic->yes()) {
            return true;
        }

        return $trinaryLogic->maybe();
    }

    private function hasConflictVariable(ClassMethod | Function_ | Closure $functionLike, string $newName): bool
    {
        return $this->betterNodeFinder->hasInstanceOfName((array) $functionLike->stmts, Variable::class, $newName);
    }

    private function isUsedInClosureUsesName(
        string $expectedName,
        ClassMethod | Function_ | Closure $functionLike
    ): bool {
        if (! $functionLike instanceof Closure) {
            return false;
        }

        return $this->betterNodeFinder->hasVariableOfName($functionLike->uses, $expectedName);
    }

    private function isUsedInForeachKeyValueVar(Variable $variable, string $currentName): bool
    {
        $previousForeach = $this->betterNodeFinder->findFirstPreviousOfTypes($variable, [Foreach_::class]);
        if ($previousForeach instanceof Foreach_) {
            if ($previousForeach->keyVar === $variable) {
                return false;
            }

            if ($previousForeach->valueVar === $variable) {
                return false;
            }

            if ($this->nodeNameResolver->isName($previousForeach->valueVar, $currentName)) {
                return true;
            }

            if ($previousForeach->keyVar === null) {
                return false;
            }

            if ($this->nodeNameResolver->isName($previousForeach->keyVar, $currentName)) {
                return true;
            }
        }

        return false;
    }

    private function isUsedInIfAndOtherBranches(Variable $variable, string $currentVariableName): bool
    {
        // is in if branches?
        $previousIf = $this->betterNodeFinder->findFirstPreviousOfTypes($variable, [If_::class]);
        if ($previousIf instanceof If_) {
            $variableUses = [];

            $variableUses[] = $this->betterNodeFinder->findVariableOfName($previousIf->stmts, $currentVariableName);

            $previousStmts = $previousIf->else !== null ? $previousIf->else->stmts : [];
            $variableUses[] = $this->betterNodeFinder->findVariableOfName($previousStmts, $currentVariableName);
            $variableUses[] = $this->betterNodeFinder->findVariableOfName($previousIf->elseifs, $currentVariableName);

            $variableUses = array_filter($variableUses);
            if (count($variableUses) > 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * @TODO Remove once ParamRenamer created
     */
    private function isRamseyUuidInterface(Param $param): bool
    {
        return $this->nodeTypeResolver->isObjectType($param, new ObjectType('Ramsey\Uuid\UuidInterface'));
    }

    /**
     * @TODO Remove once ParamRenamer created
     */
    private function isDateTimeAtNamingConvention(Param $param): bool
    {
        $type = $this->nodeTypeResolver->resolve($param);
        $type = $this->typeUnwrapper->unwrapFirstObjectTypeFromUnionType($type);
        if (! $type instanceof TypeWithClassName) {
            return false;
        }

        if (! is_a($type->getClassName(), DateTimeInterface::class, true)) {
            return false;
        }

        /** @var string $currentName */
        $currentName = $this->nodeNameResolver->getName($param);
        return (bool) Strings::match($currentName, self::AT_NAMING_REGEX . '');
    }
}
