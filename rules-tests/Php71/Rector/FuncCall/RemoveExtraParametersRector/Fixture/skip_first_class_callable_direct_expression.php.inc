<?php

namespace Rector\Tests\Php71\Rector\FuncCall\RemoveExtraParametersRector\Fixture;

final class SkipFirstClassCallableDirectExpression
{
    public function getFunctions()
    {
        $this->getTitle(...);
    }

    public function getTitle(): string
    {
        return 'title';
    }
}
