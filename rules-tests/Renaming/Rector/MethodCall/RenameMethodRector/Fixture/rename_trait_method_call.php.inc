<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture;

trait RenameTraitMethodCall
{
	public function method1()
    {
    }

	public function method2()
    {
        $this->method1();
    }

    public function withStatic()
    {
        self::method1();
        static::method1();
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture;

trait RenameTraitMethodCall
{
	public function method3()
    {
    }

	public function method2()
    {
        $this->method3();
    }

    public function withStatic()
    {
        self::method3();
        static::method3();
    }
}

?>
