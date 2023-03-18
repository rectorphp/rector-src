<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Source\SomeNode;

class SkipTypeRecursiveCallItself
{
    public function run(SomeNode $node)
    {
        return $this->oddLevel($node);
    }

    protected function oddLevel(SomeNode $node)
    {
        if ($node->next) {
            return $this->evenLevel($node->next);
        }
        return $node->value;
    }

    protected function evenLevel(SomeNode $node)
    {
        if ($node->next) {
            return $this->oddLevel($node->next);
        }
        return $node->value;
    }
}
