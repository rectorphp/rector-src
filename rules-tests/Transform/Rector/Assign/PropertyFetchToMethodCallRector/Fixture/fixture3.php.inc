<?php

namespace Rector\Tests\Transform\Rector\Assign\PropertyFetchToMethodCallRector\Fixture;

/**
 * @property string foo
 * @property string bar
 * @method string foo
 * @method string bar($string)
 */
final class Fixture3
{
    public $word = '';

    public function word(): string
    {
        return 'word';
    }

    public function myFactory(Fixture3 $myClass)
    {
        return [
            'word' => $myClass->word
        ];
    }

    public function __get(string $name)
    {
        return 'foo';
    }

    public function __call($method, $attributes)
    {
        return 'foo';
    }
}

$myClass = new Fixture3();
$word = $myClass->word;
$foo = $myClass->foo;
$myClass->bar = 'bar';

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\Assign\PropertyFetchToMethodCallRector\Fixture;

/**
 * @property string foo
 * @property string bar
 * @method string foo
 * @method string bar($string)
 */
final class Fixture3
{
    public $word = '';

    public function word(): string
    {
        return 'word';
    }

    public function myFactory(Fixture3 $myClass)
    {
        return [
            'word' => $myClass->word()
        ];
    }

    public function __get(string $name)
    {
        return 'foo';
    }

    public function __call($method, $attributes)
    {
        return 'foo';
    }
}

$myClass = new Fixture3();
$word = $myClass->word();
$foo = $myClass->foo();
$myClass->bar('bar');

?>
