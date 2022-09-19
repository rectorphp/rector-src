<?php

namespace {
    function run_me_never()
    {
        // silent deprecations, since we test them
        error_reporting(E_ALL ^ E_DEPRECATED);

        // performance boost
        \SomeNamespace\gc_disable();
    }
}

namespace SomeNamespace
{
    function gc_disable()
    {}
}
