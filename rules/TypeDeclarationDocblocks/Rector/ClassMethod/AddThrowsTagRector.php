<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Throw_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\TryCatch;
use PHPStan\Analyser\Scope;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ThrowsTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddThrowsTagRector\AddThrowsTagRectorTest
 */
final class AddThrowsTagRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var list<string> exceptions (and their subclasses) that are never documented
     */
    private array $uncheckedExceptions = [];

    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly ReflectionResolver $reflectionResolver,
    ) {}

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add a @throws tag for every checked exception a method lets escape',
            [
                new CodeSample(
                    <<<'PHP'
                        final class SomeClass
                        {
                            public function run(): void
                            {
                                throw new \RuntimeException('boom');
                            }
                        }
                        PHP
                    ,
                    <<<'PHP'
                        final class SomeClass
                        {
                            /**
                             * @throws \RuntimeException
                             */
                            public function run(): void
                            {
                                throw new \RuntimeException('boom');
                            }
                        }
                        PHP
                    ,
                ),
            ],
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        $escapingClassNames = $this->escapingClassNames((array) $node->stmts);
        if ($escapingClassNames === []) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        $hasChanged = false;
        foreach ($escapingClassNames as $className) {
            if ($this->isDocumented($phpDocInfo, $node, $className)) {
                continue;
            }

            $this->addThrowsTag($phpDocInfo, $className);
            $hasChanged = true;
        }

        if (! $hasChanged) {
            return null;
        }

        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        return $node;
    }

    /**
     * @param list<string> $configuration the unchecked exceptions
     */
    public function configure(array $configuration): void
    {
        Assert::allString($configuration);

        $this->uncheckedExceptions = $configuration;
    }

    /**
     * @param array<Node> $statements
     *
     * @return list<string>
     */
    private function escapingClassNames(array $statements): array
    {
        return $this->withoutInstancesOf(
            array_values(array_unique($this->thrownBySubNode($statements))),
            $this->uncheckedExceptions,
        );
    }

    /**
     * Walks a node, never entering a nested closure or class: what they throw is not the method's.
     *
     * @return list<string>
     */
    private function thrownBy(Node $node): array
    {
        if ($node instanceof Closure || $node instanceof ArrowFunction || $node instanceof ClassLike) {
            return [];
        }

        if ($node instanceof TryCatch) {
            return $this->thrownByTry($node);
        }

        $classNames = $this->thrownByNode($node);
        foreach ($node->getSubNodeNames() as $subNodeName) {
            $classNames = [...$classNames, ...$this->thrownBySubNode($node->{$subNodeName})];
        }

        return $classNames;
    }

    /**
     * @return list<string>
     */
    private function thrownBySubNode(mixed $subNode): array
    {
        if ($subNode instanceof Node) {
            return $this->thrownBy($subNode);
        }

        if (! is_array($subNode)) {
            return [];
        }

        $classNames = [];
        foreach ($subNode as $item) {
            $classNames = [...$classNames, ...$this->thrownBySubNode($item)];
        }

        return $classNames;
    }

    /**
     * Only the `try` body is covered by its catches: what a `catch` or `finally` throws leaves the method.
     *
     * @return list<string>
     */
    private function thrownByTry(TryCatch $try): array
    {
        $escapingFromBody = $this->withoutInstancesOf(
            $this->thrownBySubNode($try->stmts),
            $this->caughtClassNames($try),
        );

        return [
            ...$escapingFromBody,
            ...$this->thrownBySubNode($try->catches),
            ...$this->thrownBySubNode($try->finally),
        ];
    }

    /**
     * @return list<string>
     */
    private function caughtClassNames(TryCatch $try): array
    {
        $caughtClassNames = [];
        foreach ($try->catches as $catch) {
            foreach ($catch->types as $type) {
                $caughtClassName = $this->getName($type);
                if ($caughtClassName !== null) {
                    $caughtClassNames[] = $caughtClassName;
                }
            }
        }

        return $caughtClassNames;
    }

    /**
     * What the node itself throws: a `throw`, or what a called method or constructor declares in its own
     * `@throws` (the rule is meant to be run again to climb a call chain one level at a time).
     *
     * @return list<string>
     */
    private function thrownByNode(Node $node): array
    {
        if ($node instanceof Throw_) {
            return $this->getType($node->expr)->getObjectClassNames();
        }

        if ($node instanceof MethodCall || $node instanceof StaticCall || $node instanceof New_) {
            return $this->declaredByCallee($node);
        }

        return [];
    }

    /**
     * @return list<string>
     */
    private function declaredByCallee(MethodCall|StaticCall|New_ $call): array
    {
        if (! $call instanceof New_ && ($call->isFirstClassCallable() || ! $call->name instanceof Identifier)) {
            return [];
        }

        $methodReflection = match (true) {
            $call instanceof MethodCall => $this->reflectionResolver->resolveMethodReflectionFromMethodCall($call),
            $call instanceof StaticCall => $this->reflectionResolver->resolveMethodReflectionFromStaticCall($call),
            default => $this->reflectionResolver->resolveMethodReflectionFromNew($call),
        };

        return $methodReflection?->getThrowType()?->getObjectClassNames() ?? [];
    }

    private function addThrowsTag(PhpDocInfo $phpDocInfo, string $className): void
    {
        $phpDocInfo->addPhpDocTagNode(
            new PhpDocTagNode('@throws', new ThrowsTagValueNode(new IdentifierTypeNode('\\' . $className), '')),
        );
    }

    /**
     * @param list<string> $classNames
     * @param list<string> $excludedClassNames
     *
     * @return list<string>
     */
    private function withoutInstancesOf(array $classNames, array $excludedClassNames): array
    {
        return array_values(array_filter(
            $classNames,
            fn (string $className): bool => ! $this->isInstanceOfAny(new ObjectType($className), $excludedClassNames),
        ));
    }

    /**
     * @param list<string> $classNames
     */
    private function isInstanceOfAny(ObjectType $type, array $classNames): bool
    {
        return array_any($classNames, fn (string $className): bool => new ObjectType($className)->isSuperTypeOf($type)->yes());
    }

    private function isDocumented(PhpDocInfo $phpDocInfo, ClassMethod $classMethod, string $className): bool
    {
        foreach ($phpDocInfo->getTagsByName('throws') as $throwsTag) {
            if (! $throwsTag->value instanceof ThrowsTagValueNode) {
                continue;
            }

            $documentedType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
                $throwsTag->value->type,
                $classMethod,
            );
            if ($documentedType->isSuperTypeOf(new ObjectType($className))->yes()) {
                return true;
            }
        }

        return $this->isInheritedThrowType($classMethod, $className);
    }

    /**
     * An overriding method inherits the `@throws` of the method it implements or overrides.
     */
    private function isInheritedThrowType(ClassMethod $classMethod, string $className): bool
    {
        $scope = $classMethod->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return false;
        }

        $throwType = $this->reflectionResolver->resolveMethodReflectionFromClassMethod($classMethod, $scope)?->getThrowType();

        return $throwType?->isSuperTypeOf(new ObjectType($className))->yes() ?? false;
    }
}
