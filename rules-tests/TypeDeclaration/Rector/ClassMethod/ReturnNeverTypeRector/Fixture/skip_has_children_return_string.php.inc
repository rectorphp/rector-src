<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

// both classes must be in same fixture as changed part is the parent
class Testr {
    public function test() {
     	throw new \Exception("Not implemented");
    }
}

class ChildTest extends Testr {
 	public function test(): string {
     	return "foo";
    }
}
