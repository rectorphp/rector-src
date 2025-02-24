<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\GetValueMagicDir\Source;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;
use Rector\FileSystem\FilePathHelper;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class GetValueMagicDirRector extends AbstractRector
{
    public function __construct(
        private readonly ValueResolver $valueResolver,
        private readonly FilePathHelper $filePathHelper
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('', []);
    }

    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    public function refactor(Node $node): String_
    {
        $value = $this->valueResolver->getValue($node->args[0]->value);
        return new String_($this->filePathHelper->relativePath($value));
    }
}
