<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

// both classes must be in same fixture as changed part is the parent
class SkipHasChildrenReturnString {
    public function test() {
     	throw new \Exception("Not implemented");
    }
}

class SkipHasChildrenReturnStringChild extends SkipHasChildrenReturnString {
 	public function test(): string {
     	return "foo";
    }
}
