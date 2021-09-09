<?php

declare(strict_types=1);

namespace Rector\Php74\Rector\Variable;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://www.php.net/manual/en/migration74.deprecated.php
 * @see \Rector\Tests\Php74\Rector\Variable\CurlyToSquareBracketArrayStringRector\CurlyToSquareBracketArrayStringRector
 */
final class CurlyToSquareBracketArrayStringRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change curly based array and string to square bracket',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$string = 'test';
echo $string{0};
$array = ['test'];
echo $array{0};
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$string = 'test';
echo $string[0];
$array = ['test'];
echo $array[0];
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Variable::class];
    }

    /**
     * @param Variable $node
     */
    public function refactor(Node $node): ?Node
    {
        return $node;
    }
}
