<?php

declare(strict_types=1);

namespace Rector\Php72\NodeFactory;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ClosureUse;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\UnionType;
use PhpParser\NodeTraverser;
use PHPStan\Reflection\FunctionVariantWithPhpDocs;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\Php\PhpMethodReflection;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\PhpParser\Node\NodeFactory;
use Rector\Core\PhpParser\Parser\InlineCodeParser;
use Rector\Core\PhpParser\Parser\SimplePhpParser;
use Rector\Core\Util\Reflection\PrivatesAccessor;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\StaticTypeMapper\StaticTypeMapper;
use ReflectionParameter;

final class AnonymousFunctionFactory
{
    /**
     * @var string
     * @see https://regex101.com/r/jkLLlM/2
     */
    private const DIM_FETCH_REGEX = '#(\\$|\\\\|\\x0)(?<number>\d+)#';

    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly NodeFactory $nodeFactory,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly SimplePhpParser $simplePhpParser,
        private readonly AstResolver $astResolver,
        private readonly PrivatesAccessor $privatesAccessor,
        private readonly InlineCodeParser $inlineCodeParser
    ) {
    }

    /**
     * @api
     * @param Param[] $params
     * @param Stmt[] $stmts
     */
    public function create(
        array $params,
        array $stmts,
        Identifier | Name | NullableType | UnionType | ComplexType | null $returnTypeNode,
        bool $static = false
    ): Closure {
        $anonymousFunctionNode = new Closure();
        $anonymousFunctionNode->params = $params;

        if ($static) {
            $anonymousFunctionNode->static = $static;
        }

        if ($returnTypeNode instanceof Node) {
            $anonymousFunctionNode->returnType = $returnTypeNode;
        }

        $variableUses = [];
        $filteredVariables = [];

        $paramsVarNames = array_map(static fn (Param $param): string => $param->var->name, $params);
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            $stmts,
            function (Node $node) use ($paramsVarNames, &$filteredVariables, &$variableUses): ?int {
                if ($node instanceof Class_ || $node instanceof Function_ || $node instanceof Closure) {
                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                if (! $node instanceof Variable) {
                    return null;
                }

                if ($node->name === 'this') {
                    return null;
                }

                if ($node->getAttribute(AttributeKey::IS_BEING_ASSIGNED) === true) {
                    $filteredVariables[] = $node->name;
                    return null;
                }

                if (! $this->nodeNameResolver->isNames($node, array_merge($filteredVariables, $paramsVarNames))) {
                    $variableUses[] = new ClosureUse($node);

                    $filteredVariables[] = $node->name;
                }

                return null;
            }
        );

        $anonymousFunctionNode->uses = $variableUses;
        $anonymousFunctionNode->stmts = $stmts;
        return $anonymousFunctionNode;
    }

    public function createFromPhpMethodReflection(PhpMethodReflection $phpMethodReflection, Expr $expr): ?Closure
    {
        /** @var FunctionVariantWithPhpDocs $parametersAcceptorWithPhpDocs */
        $parametersAcceptorWithPhpDocs = ParametersAcceptorSelector::selectSingle($phpMethodReflection->getVariants());

        $newParams = $this->createParams($phpMethodReflection, $parametersAcceptorWithPhpDocs->getParameters());

        $innerMethodCall = $this->createInnerMethodCall($phpMethodReflection, $expr, $newParams);
        if ($innerMethodCall === null) {
            return null;
        }

        $returnTypeNode = null;
        if (! $parametersAcceptorWithPhpDocs->getReturnType() instanceof MixedType) {
            $returnTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
                $parametersAcceptorWithPhpDocs->getReturnType(),
                TypeKind::RETURN
            );
        }

        $uses = [];
        if ($expr instanceof Variable && ! $this->nodeNameResolver->isName($expr, 'this')) {
            $uses[] = new ClosureUse($expr);
        }

        // does method return something?
        $stmts = $this->resolveStmts($parametersAcceptorWithPhpDocs, $innerMethodCall);

        return new Closure([
            'params' => $newParams,
            'returnType' => $returnTypeNode,
            'uses' => $uses,
            'stmts' => $stmts,
        ]);
    }

    public function createAnonymousFunctionFromExpr(Expr $expr): ?Closure
    {
        $stringValue = $this->inlineCodeParser->stringify($expr);

        $phpCode = '<?php ' . $stringValue . ';';
        $contentStmts = $this->simplePhpParser->parseString($phpCode);

        $anonymousFunction = new Closure();

        $firstNode = $contentStmts[0] ?? null;
        if (! $firstNode instanceof Expression) {
            return null;
        }

        $stmt = $firstNode->expr;

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($stmt, static function (Node $node): Node {
            if (! $node instanceof String_) {
                return $node;
            }

            $match = Strings::match($node->value, self::DIM_FETCH_REGEX);
            if ($match === null) {
                return $node;
            }

            $matchesVariable = new Variable('matches');

            return new ArrayDimFetch($matchesVariable, new LNumber((int) $match['number']));
        });

        $anonymousFunction->stmts[] = new Return_($stmt);
        $anonymousFunction->params[] = new Param(new Variable('matches'));

        $variables = $expr instanceof Variable
            ? []
            : $this->betterNodeFinder->findInstanceOf($expr, Variable::class);

        $anonymousFunction->uses = array_map(
            static fn (Variable $variable): ClosureUse => new ClosureUse($variable),
            $variables
        );

        return $anonymousFunction;
    }

    /**
     * @param ParameterReflection[] $parameterReflections
     * @return Param[]
     */
    private function createParams(PhpMethodReflection $phpMethodReflection, array $parameterReflections): array
    {
        $classReflection = $phpMethodReflection->getDeclaringClass();
        $className = $classReflection->getName();
        $methodName = $phpMethodReflection->getName();
        /** @var ClassMethod $classMethod */
        $classMethod = $this->astResolver->resolveClassMethod($className, $methodName);

        $params = [];
        foreach ($parameterReflections as $key => $parameterReflection) {
            $variable = new Variable($parameterReflection->getName());
            $defaultExpr = $this->resolveParamDefaultExpr($parameterReflection, $key, $classMethod);
            $type = $this->resolveParamType($parameterReflection);
            $byRef = $this->isParamByReference($parameterReflection);

            $params[] = new Param($variable, $defaultExpr, $type, $byRef);
        }

        return $params;
    }

    private function resolveParamType(ParameterReflection $parameterReflection): Name|ComplexType|Identifier|null
    {
        if ($parameterReflection->getType() instanceof MixedType) {
            return null;
        }

        return $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
            $parameterReflection->getType(),
            TypeKind::PARAM
        );
    }

    private function isParamByReference(ParameterReflection $parameterReflection): bool
    {
        /** @var ReflectionParameter $reflection */
        $reflection = $this->privatesAccessor->getPrivateProperty($parameterReflection, 'reflection');
        return $reflection->isPassedByReference();
    }

    private function resolveParamDefaultExpr(
        ParameterReflection $parameterReflection,
        int $key,
        ClassMethod $classMethod
    ): ?Expr {
        if (! $parameterReflection->getDefaultValue() instanceof Type) {
            return null;
        }

        $paramDefaultExpr = $classMethod->params[$key]->default;
        if (! $paramDefaultExpr instanceof Expr) {
            return null;
        }

        // reset original node, to allow the printer to re-use the expr
        $paramDefaultExpr->setAttribute(AttributeKey::ORIGINAL_NODE, null);
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            $paramDefaultExpr,
            static function (Node $node): Node {
                $node->setAttribute(AttributeKey::ORIGINAL_NODE, null);
                return $node;
            }
        );

        return $paramDefaultExpr;
    }

    /**
     * @param Param[] $params
     */
    private function createInnerMethodCall(
        PhpMethodReflection $phpMethodReflection,
        Expr $expr,
        array $params
    ): MethodCall | StaticCall | null {
        if ($phpMethodReflection->isStatic()) {
            $expr = $this->normalizeClassConstFetchForStatic($expr);
            if ($expr === null) {
                return null;
            }

            $innerMethodCall = new StaticCall($expr, $phpMethodReflection->getName());
        } else {
            $expr = $this->resolveExpr($expr);
            if (! $expr instanceof Expr) {
                return null;
            }

            $innerMethodCall = new MethodCall($expr, $phpMethodReflection->getName());
        }

        $innerMethodCall->args = $this->nodeFactory->createArgsFromParams($params);

        return $innerMethodCall;
    }

    private function normalizeClassConstFetchForStatic(Expr $expr): null | Name | FullyQualified | Expr
    {
        if (! $expr instanceof ClassConstFetch) {
            return $expr;
        }

        if (! $this->nodeNameResolver->isName($expr->name, 'class')) {
            return $expr;
        }

        // dynamic name, nothing we can do
        $className = $this->nodeNameResolver->getName($expr->class);
        if ($className === null) {
            return null;
        }

        $name = new Name($className);
        if ($name->isSpecialClassName()) {
            return $name;
        }

        return new FullyQualified($className);
    }

    private function resolveExpr(Expr $expr): New_ | Expr | null
    {
        if (! $expr instanceof ClassConstFetch) {
            return $expr;
        }

        if (! $this->nodeNameResolver->isName($expr->name, 'class')) {
            return $expr;
        }

        // dynamic name, nothing we can do
        $className = $this->nodeNameResolver->getName($expr->class);
        return $className === null
            ? null
            : new New_(new FullyQualified($className));
    }

    /**
     * @return Stmt[]
     */
    private function resolveStmts(
        FunctionVariantWithPhpDocs $functionVariantWithPhpDocs,
        StaticCall|MethodCall $innerMethodCall
    ): array {
        if ($functionVariantWithPhpDocs->getReturnType()->isVoid()->yes()) {
            return [new Expression($innerMethodCall)];
        }

        return [new Return_($innerMethodCall)];
    }
}
