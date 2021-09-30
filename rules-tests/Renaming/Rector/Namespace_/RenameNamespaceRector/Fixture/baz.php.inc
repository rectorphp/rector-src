<?php

namespace Foo {
	interface Bar
	{
    	public function run();
    }
}

namespace Foo\Bar {
  final class Baz implements \Foo\Bar
	{
    	public function run()
	    {
    	    return 5;
	    }
	}
}
?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Namespace_\RenameNamespaceRector\Fixture;

namespace Foo {

	interface Bar
	{
    	public function run();
    }
}

namespace Foo\Tmp {
  final class Baz implements \Foo\Bar
	{
    	public function run()
	    {
    	    return 5;
	    }
	}
}
?>
