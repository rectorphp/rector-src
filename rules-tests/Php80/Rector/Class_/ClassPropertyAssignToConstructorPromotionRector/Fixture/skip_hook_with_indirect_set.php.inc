<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class SkipHookWithIndirectSet
{
    public mixed $value {
    	get {
        	return $this->value;
        }
        set {
            if ($this->optionUsedInValueSetter) {
            	$this->value = "changed value";
            }
            $this->value = $value;
        }
    }

    public function __construct(
    	mixed $value,
        bool $optionUsedInValueSetter = false,
    ) {
    	$this->value = $value;
    }
}