<?php

namespace Rector\Tests\Transform\Rector\Assign\PropertyFetchToMethodCallRector\Fixture;

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
}

$myClass = new Fixture3();
$word = $myClass->word;

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\Assign\PropertyFetchToMethodCallRector\Fixture;

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
}

$myClass = new Fixture3();
$word = $myClass->word();

?>
