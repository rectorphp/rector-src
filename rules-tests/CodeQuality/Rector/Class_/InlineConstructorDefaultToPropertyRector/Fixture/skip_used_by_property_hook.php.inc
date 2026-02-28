<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

final class SkipUsedByPropertyHook
{
    private bool $isConstructed = false;

    public mixed $value = null {
    	get {
        	return $this->value;
        }
        set {
        	if (!$this->isConstructed) {
            	$this->value = $value;
            	return;
            }

            if (!is_string($value)) {
            	throw new \TypeError('$value must be a string when it is already constructed!');
            }

            $this->value = $value;
        }
    }

    public function __construct(
    	mixed $value = null,
    ) {
        $this->value = $value;
    	$this->isConstructed = true;
    }
}
