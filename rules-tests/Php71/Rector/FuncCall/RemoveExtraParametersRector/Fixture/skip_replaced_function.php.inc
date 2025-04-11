<?php

// different nameespace for this fixture is on purpos to avoid overlap other fixture
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