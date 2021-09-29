<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

function foo($foo) { $foo(); }

foo(static fn() => 1 == 1 ? false : null);

foo(fn() => false);

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

function foo($foo) { $foo(); }

foo(static fn(): ?bool => 1 == 1 ? false : null);

foo(fn(): bool => false);

?>
