<?php

declare(strict_types=1);

namespace Rector\Transform\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Transform\ValueObject\SingleToManyMethod;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Transform\Rector\ClassMethod\SingleToManyMethodRector\SingleToManyMethodRectorTest
 */
final class SingleToManyMethodRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @api
     * @deprecated
     * @var string
     */
    public const SINGLES_TO_MANY_METHODS = 'singles_to_many_methods';

    /**
     * @var SingleToManyMethod[]
     */
    private array $singleToManyMethods = [];

    public function __construct(
        private readonly PhpDocTypeChanger $phpDocTypeChanger
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change method that returns single value to multiple values', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function getNode(): string
    {
        return 'Echo_';
    }
}
CODE_SAMPLE
,
                <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @return string[]
     */
    public function getNodes(): array
    {
        return ['Echo_'];
    }
}
CODE_SAMPLE
            ,
                [new SingleToManyMethod('SomeClass', 'getNode', 'getNodes')]
            ),
        ]);
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
        $classLike = $this->betterNodeFinder->findParentType($node, ClassLike::class);
        if (! $classLike instanceof ClassLike) {
            return null;
        }

        foreach ($this->singleToManyMethods as $singleToManyMethod) {
            if (! $this->isObjectType($classLike, $singleToManyMethod->getObjectType())) {
                continue;
            }

            if (! $this->isName($node, $singleToManyMethod->getSingleMethodName())) {
                continue;
            }

            $node->name = new Identifier($singleToManyMethod->getManyMethodName());
            $this->keepOldReturnTypeInDocBlock($node);

            $node->returnType = new Identifier('array');
            $this->wrapReturnValueToArray($node);

            return $node;
        }

        return null;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $singleToManyMethods = $configuration[self::SINGLES_TO_MANY_METHODS] ?? $configuration;
        Assert::allIsAOf($singleToManyMethods, SingleToManyMethod::class);

        $this->singleToManyMethods = $singleToManyMethods;
    }

    private function keepOldReturnTypeInDocBlock(ClassMethod $classMethod): void
    {
        // keep old return type in the docblock
        $oldReturnType = $classMethod->returnType;
        if ($oldReturnType === null) {
            return;
        }

        $staticType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($oldReturnType);
        $arrayType = new ArrayType(new MixedType(), $staticType);

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);
        $this->phpDocTypeChanger->changeReturnType($phpDocInfo, $arrayType);
    }

    private function wrapReturnValueToArray(ClassMethod $classMethod): void
    {
        $this->traverseNodesWithCallable((array) $classMethod->stmts, function (Node $node) {
            if (! $node instanceof Return_) {
                return null;
            }

            $node->expr = $this->nodeFactory->createArray([$node->expr]);
            return null;
        });
    }
}
