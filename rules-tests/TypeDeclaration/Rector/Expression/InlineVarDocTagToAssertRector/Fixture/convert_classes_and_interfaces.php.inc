<?php

namespace Rector\Tests\TypeDeclaration\Rector\Expression\InlineVarDocTagToAssertRector\Fixture;

use Foo\Bar;
use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\FooInterface;
use Traversable;

/** @var Bar $foo */
$foo = getBar();

/** @var \Foo\Bar $foo */
$foo = getFooBar();

/** @var FooInterface $foo */
$foo = getFooInterface();

/** @var Traversable $foo */
$foo = getTraversable();

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Expression\InlineVarDocTagToAssertRector\Fixture;

use Foo\Bar;
use Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Source\FooInterface;
use Traversable;

$foo = getBar();
assert($foo instanceof Bar);

$foo = getFooBar();
assert($foo instanceof \Foo\Bar);

$foo = getFooInterface();
assert($foo instanceof FooInterface);

$foo = getTraversable();
assert($foo instanceof Traversable);

?>
