<?php

namespace Rector\Tests\PSR4\Rector\FileWithoutNamespace\CollectPSR4ComposerAutoloadNamespaceRenamesRector\Fixture {
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
