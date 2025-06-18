<?php

namespace Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\Source;

trait ValuesTrait
{
    private array $values = [];

	public function addValue(int $value) {
    	$this->values[] = $value;
    }
}
