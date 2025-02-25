<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\GetValueMagicDir\Source;

use PhpParser\Builder;
use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Scalar\String_;
use Rector\FileSystem\FilePathHelper;
use Rector\PhpParser\Node\NodeFactory;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class GetValueMagicDirRector extends AbstractRector
{
    public function __construct(
        private readonly ValueResolver $valueResolver,
        private readonly FilePathHelper $filePathHelper,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('', []);
    }

    public function getNodeTypes(): array
    {
        return [FuncCall::class, New_::class];
    }

    public function refactor(Node $node): String_|Array_
    {
        $value = $this->valueResolver->getValue($node->args[0]->value);

        if ($node instanceof FuncCall) {
            return new String_($this->filePathHelper->relativePath($value));
        }

        $value['bar']['foo'] = $this->filePathHelper->relativePath($value['bar']['foo']);
        return $this->nodeFactory->createArray($value);
    }
}
