<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

// both classes must be in same fixture as changed part is the parent
class Testr2 {
    public function test() {
     	throw new \Exception("Not implemented");
    }
}

class ChildTest2 extends Testr2 {
 	public function test(): mixed {
     	return "foo";
    }
}
