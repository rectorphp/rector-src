<?php

namespace App {
    function execute()
    {
        echo 'execute from named namespace';
    }
}

namespace
{
    function execute()
    {
        echo 'execute from non-named namespace';
    }
}
