<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source;

trait ValuesTraitAbstract
{
    private array $values = [];

	abstract public function addValue(int $value);
}
