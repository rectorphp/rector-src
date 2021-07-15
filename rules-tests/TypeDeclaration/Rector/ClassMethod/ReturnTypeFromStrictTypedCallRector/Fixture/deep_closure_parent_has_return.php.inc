<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

class App
{
    public static function init(): App
    {
        return new App;
    }
}

function () {

    function () {
        return App::init();
    };

    return App::init();

};

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

class App
{
    public static function init(): App
    {
        return new App;
    }
}

function (): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture\App {

    function (): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture\App {
        return App::init();
    };

    return App::init();

};

?>
