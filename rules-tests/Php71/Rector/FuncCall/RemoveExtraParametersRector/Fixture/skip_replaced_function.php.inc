<?php

// different namespace for this fixture is on purpose to avoid overlap other fixture
namespace SomeApp
{
    final class SkipReplacedFunction
    {
        function skipReplacedFunction()
        {
            \strlen('asdf');
        }
    }

    function strlen()
    {
    }
}


?>