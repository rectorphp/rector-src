<?php

namespace Rector\Tests\Php70\Rector\MethodCall\ThisCallOnStaticMethodToStaticCallRector\Fixture;

/**
 * @method static static staticReturnStatic()
 * @method static static|\Rector\Tests\Php70\Rector\MethodCall\ThisCallOnStaticMethodToStaticCallRector\Fixture\StaticMethodInAnnotation|string[] staticReturnMixed()
 * @method static withoutReturnType()
 * @method static withoutParameters
 * @method \Rector\Tests\Php70\Rector\MethodCall\ThisCallOnStaticMethodToStaticCallRector\Fixture\StaticMethodInAnnotation|static returnStatic
 */
trait StaticMethodInAnnotation
{
    public function run()
    {
        $this->staticReturnStatic();
        $this->staticReturnMixed();
        $this->withoutReturnType();
        $this->withoutParameters();
        $this->returnStatic();
    }
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\MethodCall\ThisCallOnStaticMethodToStaticCallRector\Fixture;

/**
 * @method static static staticReturnStatic()
 * @method static static|\Rector\Tests\Php70\Rector\MethodCall\ThisCallOnStaticMethodToStaticCallRector\Fixture\StaticMethodInAnnotation|string[] staticReturnMixed()
 * @method static withoutReturnType()
 * @method static withoutParameters
 * @method \Rector\Tests\Php70\Rector\MethodCall\ThisCallOnStaticMethodToStaticCallRector\Fixture\StaticMethodInAnnotation|static returnStatic
 */
trait StaticMethodInAnnotation
{
    public function run()
    {
        static::staticReturnStatic();
        static::staticReturnMixed();
        static::withoutReturnType();
        static::withoutParameters();
        $this->returnStatic();
    }
}

?>
