<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\Property;

use PhpParser\Node;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php80\Rector\Property\NestedAnnotationToAttributeRector\NestedAnnotationToAttributeRectorTest
 */
final class NestedAnnotationToAttributeRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var mixed[]
     */
    private $nestedAnnotationsToAttributes = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Changed nested annotations to attributes', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
use Doctrine\ORM\Mapping as ORM;

class SomeEntity
{
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Example", inversedBy="backLink")
     * @ORM\JoinTable(name="join_table_name",
     *     joinColumns={@ORM\JoinColumn(name="origin_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="target_id", referencedColumnName="id")}
     * )
     */
    private $collection;
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use Doctrine\ORM\Mapping as ORM;

class SomeEntity
{
    #[ORM\ManyToMany(targetEntity: 'App\Entity\Example', inversedBy: 'backLink')]
    #[ORM\JoinTable(name: 'join_table_name')]
    #[ORM\JoinColumn(name: 'origin_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'target_id', referencedColumnName: 'id')]
    private $collection;
}
CODE_SAMPLE
                ,
                [[
                    'old_value' => 'newValue',
                ]]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [\PhpParser\Node\Stmt\Property::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\Property $node
     */
    public function refactor(Node $node): ?Node
    {
        // change the node

        return $node;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $this->nestedAnnotationsToAttributes = $configuration;
    }
}
