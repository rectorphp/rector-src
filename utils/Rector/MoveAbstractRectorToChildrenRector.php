<?php

declare(strict_types=1);

namespace Rector\Utils\Rector;

// e.g. to move PhpDocInfo to the particular rule itself
use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\NodeManipulator\ClassDependencyManipulator;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class MoveAbstractRectorToChildrenRector extends AbstractRector
{
    /**
     * @var array<string, string>
     */
    private const PROPERTIES_TO_TYPES = [
        'phpDocInfoFactory' => PhpDocInfoFactory::class,
        'valueResolver' => ValueResolver::class,
        'betterNodeFinder' => BetterNodeFinder::class,
        'staticTypeMapper' => StaticTypeMapper::class,
    ];

    public function __construct(
        private readonly ClassDependencyManipulator $classDependencyManipulator,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Move parent class autowired dependency to constructor of children', []);
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->isAbstract()) {
            return null;
        }

        if (! $this->isObjectType($node, new ObjectType('Rector\Rector\AbstractRector'))) {
            return null;
        }

        $typesToAdd = [];

        // has dependency on X type?
        $this->traverseNodesWithCallable($node->stmts, function (Node $node) use (&$typesToAdd) {
            if (! $node instanceof PropertyFetch) {
                return null;
            }

            if (! $this->isName($node->var, 'this')) {
                return null;
            }

            foreach (self::PROPERTIES_TO_TYPES as $propertyName => $propertyType) {
                if (! $this->isName($node->name, $propertyName)) {
                    continue;
                }

                $typesToAdd[$propertyName] = $propertyType;
            }
        });

        // remove already added properties

        if ($typesToAdd === []) {
            return null;
        }

        $hasChanged = false;

        foreach ($typesToAdd as $propertyNameToAdd => $propertyTypeToAdd) {
            // skip if property already exists
            if ($node->getProperty($propertyNameToAdd) instanceof Property) {
                continue;
            }

            $this->classDependencyManipulator->addConstructorDependency(
                $node,
                new PropertyMetadata($propertyNameToAdd, new ObjectType($propertyTypeToAdd))
            );

            $hasChanged = true;
        }

        if (! $hasChanged) {
            return null;
        }

        return $node;
    }
}
