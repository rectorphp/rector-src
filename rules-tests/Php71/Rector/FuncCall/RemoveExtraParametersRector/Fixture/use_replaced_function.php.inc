<?php

// different namespace for this fixture is on purpose to avoid overlap other fixture
namespace SomeApp2
{
    final class UseReplacedFunction
    {
        function run()
        {
            echo strlen('asdf');
        }
    }

    function strlen()
    {
        echo 'test';
    }
}


?>
-----
<?php

// different namespace for this fixture is on purpose to avoid overlap other fixture
namespace SomeApp2
{
    final class UseReplacedFunction
    {
        function run()
        {
            echo strlen();
        }
    }

    function strlen()
    {
        echo 'test';
    }
}


?>