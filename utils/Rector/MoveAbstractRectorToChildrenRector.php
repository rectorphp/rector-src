<?php

declare(strict_types=1);

namespace Rector\Utils\Rector;

// e.g. to move PhpDocInfo to the particular rule itself
use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Core\NodeManipulator\ClassDependencyManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class MoveAbstractRectorToChildrenRector extends AbstractRector
{
    /**
     * @var array<string, string>
     */
    private const PROPERTIES_TO_TYPES = [
        'phpDocInfoFactory' => PhpDocInfoFactory::class,
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
        return [Node\Stmt\Class_::class];
    }

    /**
     * @param Node\Stmt\Class_ $node
     */
    public function refactor(Node $node)
    {
        if ($node->isAbstract()) {
            return null;
        }

        if (! $this->isObjectType($node, new ObjectType('Rector\Core\Rector\AbstractRector'))) {
            return null;
        }

        $typesToAdd = [];

        // has dependency on X type?
        $this->traverseNodesWithCallable($node->stmts, function (\PhpParser\Node $node) use (&$typesToAdd) {
            if (! $node instanceof Node\Expr\PropertyFetch) {
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

        if ($typesToAdd === []) {
            return null;
        }

        foreach ($typesToAdd as $propertyNameToAdd => $propertyTypeToAdd) {
            $this->classDependencyManipulator->addConstructorDependency(
                $node,
                new PropertyMetadata($propertyNameToAdd, new ObjectType($propertyTypeToAdd))
            );
        }

        return $node;
    }
}
