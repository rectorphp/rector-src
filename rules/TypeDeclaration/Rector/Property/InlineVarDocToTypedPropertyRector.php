<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\Property;

use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use Rector\TypeDeclaration\Rector\AbstractInlineVarDocToTypedScopedPropertyRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class InlineVarDocToTypedPropertyRector extends AbstractInlineVarDocToTypedScopedPropertyRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Changes property `@var` annotations from annotation to type for all modifiers.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    /**
     * @var int
     */
    private $count;

    /**
     * @var int
     */
    protected $count2;

    /**
     * @var int
     */
    public $count3;
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    private int $count;

    protected int $count2;

    public int $count2;
}
CODE_SAMPLE
                ),
            ]
        );
    }

    protected function shouldSkipProperty(Property $property, Scope $scope): bool
    {
        return $this->shouldSkip($property, $scope);
    }
}
